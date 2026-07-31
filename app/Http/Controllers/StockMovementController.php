<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Event;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockMovementController extends Controller
{
    public function index()
    {
        // daftar event yang punya transaksi, diurutkan dari yang terbaru
        $events = Event::withCount('movements')
            ->has('movements')
            ->latest('event_date')
            ->paginate(15);

        $nonEventCount = StockMovement::whereNull('event_id')->count();

        return view('movements.index', compact('events', 'nonEventCount'));
    }

    public function byEvent(Event $event)
    {
        $movements = $event->movements()->with('item')->orderBy('movement_date')->get();
        return view('movements.by-event', compact('event', 'movements'));
    }

    public function nonEvent()
    {
        $movements = StockMovement::whereNull('event_id')
            ->with('item')
            ->latest('movement_date')
            ->paginate(20);

        return view('movements.non-event', compact('movements'));
    }

    // ==== BARANG KELUAR ====

    public function createOut()
    {
        $items = Item::where('stock_available', '>', 0)->get();
        $events = Event::where('status', '!=', 'selesai')->get();
        return view('movements.create-out', compact('items', 'events'));
    }

    public function storeOut(Request $request)
    {
        $validated = $request->validate([
            'item_id' => 'required|exists:items,id',
            'event_id' => 'nullable|exists:events,id',
            'quantity' => 'required|integer|min:1',
            'movement_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $item = Item::findOrFail($validated['item_id']);

        if ($validated['quantity'] > $item->stock_available) {
            return back()->withErrors(['quantity' => "Stok tersedia cuma {$item->stock_available}, gak cukup untuk {$validated['quantity']}"])->withInput();
        }

        DB::transaction(function () use ($validated, $item) {
            StockMovement::create([
                'item_id' => $validated['item_id'],
                'event_id' => $validated['event_id'] ?? null,
                'type' => 'keluar',
                'quantity' => $validated['quantity'],
                'condition' => null,
                'movement_date' => $validated['movement_date'],
                'notes' => $validated['notes'] ?? null,
            ]);

            $item->decrement('stock_available', $validated['quantity']);
        });

        return redirect()->route('movements.index')->with('success', 'Barang keluar berhasil dicatat');
    }

    // ==== BARANG MASUK (KEMBALI) ====

    public function createIn()
    {
        $items = Item::all();
        $events = Event::all();
        return view('movements.create-in', compact('items', 'events'));
    }

    public function storeIn(Request $request)
    {
        $validated = $request->validate([
            'item_id' => 'required|exists:items,id',
            'event_id' => 'nullable|exists:events,id',
            'qty_baik' => 'nullable|integer|min:0',
            'qty_rusak' => 'nullable|integer|min:0',
            'qty_hilang' => 'nullable|integer|min:0',
            'movement_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $qtyBaik = $validated['qty_baik'] ?? 0;
        $qtyRusak = $validated['qty_rusak'] ?? 0;
        $qtyHilang = $validated['qty_hilang'] ?? 0;

        if ($qtyBaik + $qtyRusak + $qtyHilang === 0) {
            return back()->withErrors(['qty_baik' => 'Isi minimal salah satu jumlah kondisi barang'])->withInput();
        }

        DB::transaction(function () use ($validated, $qtyBaik, $qtyRusak, $qtyHilang) {
            $item = Item::findOrFail($validated['item_id']);

            foreach (['baik' => $qtyBaik, 'rusak' => $qtyRusak, 'hilang' => $qtyHilang] as $condition => $qty) {
                if ($qty > 0) {
                    StockMovement::create([
                        'item_id' => $validated['item_id'],
                        'event_id' => $validated['event_id'] ?? null,
                        'type' => 'masuk',
                        'quantity' => $qty,
                        'condition' => $condition,
                        'movement_date' => $validated['movement_date'],
                        'notes' => $validated['notes'] ?? null,
                    ]);
                }
            }

            // hanya barang kondisi "baik" yang masuk balik ke stok tersedia
            $item->increment('stock_available', $qtyBaik);
        });

        return redirect()->route('movements.index')->with('success', 'Barang kembali berhasil dicatat');
    }
}
