<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Item;
use App\Models\OpnameRecord;

class DashboardController extends Controller
{
    public function index()
    {
        $totalItems = Item::count();
        $totalStockAvailable = Item::sum('stock_available');
        $totalStockOut = Item::sum('stock_total') - $totalStockAvailable;

        $lowStockItems = Item::whereRaw('stock_available <= (stock_total * 0.2) AND stock_total > 0')
            ->with('category')
            ->get();

        // ganti dari movement mentah jadi event terbaru yang punya transaksi
        $recentEvents = Event::withCount('movements')
            ->has('movements')
            ->oldest('event_date')
            ->take(5)
            ->get();

        $recentDiscrepancies = OpnameRecord::with('item')
            ->where('difference', '!=', 0)
            ->where('session_date', '>=', now()->subDays(30))
            ->oldest('session_date')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalItems',
            'totalStockAvailable',
            'totalStockOut',
            'lowStockItems',
            'recentEvents',
            'recentDiscrepancies'
        ));
    }
}
