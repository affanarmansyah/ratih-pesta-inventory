<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\OpnameController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StockMovementController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('categories', CategoryController::class);
    Route::resource('items', ItemController::class);

    Route::get('movements', [StockMovementController::class, 'index'])->name('movements.index');
    Route::post('movements/keluar', [StockMovementController::class, 'storeOut'])->name('movements.store-out');
    Route::get('movements/keluar/create', [StockMovementController::class, 'selectEventOut'])->name('movements.create-out.select');
    Route::get('movements/keluar/create/{event}', [StockMovementController::class, 'createOut'])->name('movements.create-out');

    Route::get('movements/masuk/create', [StockMovementController::class, 'selectEventIn'])->name('movements.create-in.select');
    Route::get('movements/masuk/create/{event}', [StockMovementController::class, 'createIn'])->name('movements.create-in');
    Route::get('movements/masuk/create-manual', [StockMovementController::class, 'createInManual'])->name('movements.create-in-manual');
    Route::post('movements/masuk', [StockMovementController::class, 'storeIn'])->name('movements.store-in');
    Route::get('movements/event/{event}', [StockMovementController::class, 'byEvent'])->name('movements.by-event');
    Route::get('movements/non-event', [StockMovementController::class, 'nonEvent'])->name('movements.non-event');
    Route::post('movements/{movement}/void', [StockMovementController::class, 'void'])->name('movements.void');

    Route::get('opname', [OpnameController::class, 'create'])->name('opname.create');
    Route::post('opname', [OpnameController::class, 'store'])->name('opname.store');
    Route::get('opname/history', [OpnameController::class, 'history'])->name('opname.history');

    Route::resource('events', EventController::class);
});

require __DIR__ . '/auth.php';
