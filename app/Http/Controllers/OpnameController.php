<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOpnameRequest;
use App\Models\Item;
use App\Models\OpnameRecord;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class OpnameController extends Controller
{
    public function create()
    {
        // system_qty diambil dari stock_available saat ini
        $items = Item::with('category')->orderBy('category_id')->get();
        return view('opname.create', compact('items'));
    }

    public function store(StoreOpnameRequest $request)
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated) {
            foreach ($validated['physical_qty'] as $itemId => $physicalQty) {
                if ($physicalQty === null || $physicalQty === '') {
                    continue; // barang yang gak diisi dianggap belum dicek, skip
                }

                $item = Item::findOrFail($itemId);
                $systemQty = $item->stock_available;
                $difference = $physicalQty - $systemQty;

                OpnameRecord::create([
                    'user_id' => auth()->id(),
                    'item_id' => $itemId,
                    'session_date' => $validated['session_date'],
                    'system_qty' => $systemQty,
                    'physical_qty' => $physicalQty,
                    'difference' => $difference,
                    'notes' => $validated['notes'][$itemId] ?? null,
                ]);

                // kalau ada selisih, sesuaikan stok sistem supaya cocok dengan hasil fisik
                if ($difference !== 0) {
                    $item->update(['stock_available' => $physicalQty]);
                }
            }
        });

        return redirect()->route('opname.history')->with('success', 'Opname berhasil disimpan');
    }

    public function history(Request $request)
    {
        $query = OpnameRecord::with('item');

        if ($request->filled('item_id')) {
            $query->where('item_id', $request->item_id);
        }
        if ($request->filled('from')) {
            $query->whereDate('session_date', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->whereDate('session_date', '<=', $request->to);
        }

        $records = $query->oldest('session_date')->paginate(30)->withQueryString();
        $items = Item::orderBy('name')->get();

        return view('opname.history', compact('records', 'items'));
    }
}
