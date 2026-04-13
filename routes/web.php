<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Insights\InsightsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Commodities\CommodityController;
use App\Http\Controllers\Listings\ListingController;
use App\Http\Controllers\Notifications\NotificationController;
use App\Http\Controllers\Notifications\FollowController;

// Admin Controllers
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\ListingController as AdminListingController;
use App\Http\Controllers\Admin\CommodityController as AdminCommodityController;
use App\Http\Controllers\Admin\ScraperController;
use App\Http\Controllers\Admin\NotificationLogController;


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

Route::prefix('admin')
    ->middleware(['auth', 'admin'])
    ->name('admin.')
    ->group(function () {

        Route::get('/', [DashboardController::class, 'index'])
             ->name('dashboard');

        // Users
        Route::get('users', [AdminUserController::class, 'index'])
             ->name('users');
        Route::get('users/{user}', [AdminUserController::class, 'show'])
             ->name('users.show');
        Route::patch('users/{user}/ban', [AdminUserController::class, 'ban'])
             ->name('users.ban');
        Route::patch('users/{user}/unban', [AdminUserController::class, 'unban'])
             ->name('users.unban');

        // Listings
        Route::get('listings', [AdminListingController::class, 'index'])
             ->name('listings');
        Route::patch('listings/{listing}/toggle', [AdminListingController::class, 'toggleStatus'])
             ->name('listings.toggle');
        Route::delete('listings/{listing}', [AdminListingController::class, 'destroy'])
             ->name('listings.destroy');

        // Commodities
        Route::get('commodities', [AdminCommodityController::class, 'index'])
             ->name('commodities');
        Route::get('commodities/create', [AdminCommodityController::class, 'create'])
             ->name('commodities.create');
        Route::post('commodities', [AdminCommodityController::class, 'store'])
             ->name('commodities.store');
        Route::patch('commodities/{commodity}/toggle', [AdminCommodityController::class, 'toggleActive'])
             ->name('commodities.toggle');

        // Scraper
        Route::get('scraper', [ScraperController::class, 'index'])
             ->name('scraper');
        Route::post('scraper/run', [ScraperController::class, 'run'])
             ->name('scraper.run');

        // Notifications log
        Route::get('notifications', [NotificationLogController::class, 'index'])
             ->name('notifications');
    });


require __DIR__.'/auth.php';
