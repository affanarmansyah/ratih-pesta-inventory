<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStockMovementInRequest;
use App\Http\Requests\StoreStockMovementOutRequest;
use App\Models\Item;
use App\Models\Event;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockMovementController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::withCount('movements')->has('movements');

        if ($request->filled('search')) {
            $query->where('customer_name', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('from')) {
            $query->whereDate('event_date', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->whereDate('event_date', '<=', $request->to);
        }

        $events = $query->oldest('event_date')->paginate(15)->withQueryString();
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
            ->oldest('movement_date')
            ->paginate(20);

        return view('movements.non-event', compact('movements'));
    }

    // ==== BARANG KELUAR ====

    public function selectEventOut()
    {
        $events = Event::where('status', '!=', 'selesai')->orderBy('event_date')->get();
        return view('movements.select-event-out', compact('events'));
    }

    public function createOut(Event $event)
    {
        $items = Item::where('stock_available', '>', 0)->get();
        return view('movements.create-out', compact('items', 'event'));
    }

    public function storeOut(StoreStockMovementOutRequest $request)
    {
        $validated = $request->validated();

        $item = Item::findOrFail($validated['item_id']);

        DB::transaction(function () use ($validated, $item) {
            StockMovement::create([
                'user_id' => auth()->id(),
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

    // Step 1: pilih event yang masih ada barang belum kembali
    public function selectEventIn()
    {
        $eventIds = DB::table('stock_movements')
            ->whereNotNull('event_id')
            ->whereNull('voided_at')
            ->groupBy('event_id')
            ->havingRaw("SUM(CASE WHEN type='keluar' THEN quantity ELSE -quantity END) > 0")
            ->pluck('event_id');

        $events = Event::whereIn('id', $eventIds)->orderBy('event_date')->get();

        return view('movements.select-event-in', compact('events'));
    }

    // Step 2: form barang kembali, item-nya udah difilter sesuai event
    public function createIn(Event $event)
    {
        $outstanding = DB::table('stock_movements')
            ->where('event_id', $event->id)
            ->whereNull('voided_at')
            ->selectRaw("item_id, SUM(CASE WHEN type='keluar' THEN quantity ELSE -quantity END) as outstanding")
            ->groupBy('item_id')
            ->having('outstanding', '>', 0)
            ->pluck('outstanding', 'item_id');

        $items = Item::whereIn('id', $outstanding->keys())->get();

        return view('movements.create-in', compact('event', 'items', 'outstanding'));
    }

    // Jalur alternatif: tanpa event (misal penyesuaian manual)
    public function createInManual()
    {
        $items = Item::all();
        $events = Event::all();
        return view('movements.create-in-manual', compact('items', 'events'));
    }

    public function storeIn(StoreStockMovementInRequest $request)
    {
        $validated = $request->validated();

        $qtyBaik = $validated['qty_baik'] ?? 0;
        $qtyRusak = $validated['qty_rusak'] ?? 0;
        $qtyHilang = $validated['qty_hilang'] ?? 0;

        DB::transaction(function () use ($validated, $qtyBaik, $qtyRusak, $qtyHilang) {
            $item = Item::findOrFail($validated['item_id']);

            foreach (['baik' => $qtyBaik, 'rusak' => $qtyRusak, 'hilang' => $qtyHilang] as $condition => $qty) {
                if ($qty > 0) {
                    StockMovement::create([
                        'user_id' => auth()->id(),
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

            $item->increment('stock_available', $qtyBaik);
        });

        return redirect()->route('movements.index')->with('success', 'Barang kembali berhasil dicatat');
    }

    public function void(StockMovement $movement)
    {
        if ($movement->voided_at) {
            return back()->with('error', 'Transaksi ini sudah dibatalkan sebelumnya');
        }

        DB::transaction(function () use ($movement) {
            $item = $movement->item;

            if ($movement->type === 'keluar') {
                $item->increment('stock_available', $movement->quantity);
            } elseif ($movement->type === 'masuk' && $movement->condition === 'baik') {
                $item->decrement('stock_available', $movement->quantity);
            }

            $movement->update([
                'voided_at' => now(),
                'voided_by' => auth()->id(),
            ]);
        });

        return back()->with('success', 'Transaksi berhasil dibatalkan, stok sudah disesuaikan kembali');
    }
}
