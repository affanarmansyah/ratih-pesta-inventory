<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreItemRequest;
use App\Http\Requests\UpdateItemRequest;
use App\Models\Category;
use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Item::with('category');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $items = $query->orderBy('name')->paginate(20)->withQueryString();
        $categories = Category::all();

        return view('items.index', compact('items', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('items.create', compact('categories'));
    }

    public function store(StoreItemRequest $request)
    {
        $validated = $request->validated();
        $validated['stock_available'] = $validated['stock_total'];

        Item::create($validated);

        return redirect()->route('items.index')->with('success', 'Barang berhasil ditambahkan');
    }

    public function edit(Item $item)
    {
        $categories = Category::all();
        return view('items.edit', compact('item', 'categories'));
    }

    public function update(UpdateItemRequest $request, Item $item)
    {
        $validated = $request->validated();
        $validated['stock_available'] = $validated['stock_total'];

        $item->update($validated);

        return redirect()->route('items.index')->with('success', 'Barang berhasil diupdate');
    }

    public function destroy(Item $item)
    {
        $item->delete();

        return redirect()->route('items.index')->with('success', 'Item berhasil dihapus');
    }
}
