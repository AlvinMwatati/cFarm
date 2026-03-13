<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Commodities\CommodityController;
use App\Http\Controllers\Listings\ListingController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('commodities', CommodityController::class)
        ->only(['index', 'show', 'create', 'store']);

    Route::resource('listings', ListingController::class)
         ->only(['index', 'show', 'create', 'store']);

    Route::get('my-listings', [ListingController::class, 'myListings'])
         ->name('listings.mine');

    Route::patch('listings/{listing}/toggle-status', [ListingController::class, 'toggleStatus'])
         ->name('listings.toggle-status');
});


require __DIR__.'/auth.php';
