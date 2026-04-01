<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Insights\InsightsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Commodities\CommodityController;
use App\Http\Controllers\Listings\ListingController;
use App\Http\Controllers\Notifications\NotificationController;
use App\Http\Controllers\Notifications\FollowController;


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

    Route::get('insights', [InsightsController::class, 'index'])
         ->name('insights.index');

    Route::get('insights/{commodity}', [InsightsController::class, 'show'])
         ->name('insights.show');


     // Notifications bell
    Route::get('notifications', [NotificationController::class, 'index'])
         ->name('notifications.index');
    Route::delete('notifications/{id}', [NotificationController::class, 'destroy'])
         ->name('notifications.destroy');
    Route::delete('notifications', [NotificationController::class, 'destroyAll'])
         ->name('notifications.destroyAll');

    // Following
    Route::post('commodities/{commodity}/follow', [FollowController::class, 'follow'])
         ->name('commodities.follow');
    Route::delete('commodities/{commodity}/follow', [FollowController::class, 'unfollow'])
         ->name('commodities.unfollow');
    Route::get('my-follows', [FollowController::class, 'index'])
         ->name('follows.index');
});


require __DIR__.'/auth.php';
