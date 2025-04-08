<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SalesAndPurchaseController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\AdvertisementController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MinigameController;
use App\Http\Controllers\ContractController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');

// Contract routes
Route::get('/contracts', [ContractController::class, 'index'])->name('contracts.index');
Route::post('/contracts/accept', [ContractController::class, 'accept'])->name('contracts.accept');
Route::get('/contracts/create', [ContractController::class, 'create'])->name('contracts.create');
Route::post('/contracts', [ContractController::class, 'store'])->name('contracts.store');
Route::get('/contracts/{contract}/edit', [ContractController::class, 'edit'])->name('contracts.edit');
Route::put('/contracts/{contract}', [ContractController::class, 'update'])->name('contracts.update');
Route::delete('/contracts/{contract}', [ContractController::class, 'destroy'])->name('contracts.destroy');
Route::get('/contracts/export-pdf', [ContractController::class, 'exportPdf'])->name('contracts.export-pdf');

// Authenticated routes that require contract acceptance
Route::middleware(['auth', 'contracts.accepted'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    // Auction routes
    Route::get('/dashboard/auctions/calendar', [SalesAndPurchaseController::class, 'auctionCalendar'])->name('auctions.calendar');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Minigame routes
    Route::prefix('minigames')->name('minigames.')->group(function () {
        Route::get('/', [MinigameController::class, 'index'])->name('index');
        Route::get('/game1/intro', [MinigameController::class, 'game1Intro'])->name('game1.intro');
        Route::get('/game1', [MinigameController::class, 'game1'])->name('game1');
        Route::get('/game2/intro', [MinigameController::class, 'game2Intro'])->name('game2.intro');
        Route::get('/game2', [MinigameController::class, 'game2'])->name('game2');
        Route::get('/game3/intro', [MinigameController::class, 'game3Intro'])->name('game3.intro');
        Route::get('/game3', [MinigameController::class, 'game3'])->name('game3');
        Route::get('/game4/intro', [MinigameController::class, 'game4Intro'])->name('game4.intro');
        Route::get('/game4', [MinigameController::class, 'game4'])->name('game4');
        Route::post('/submit-score', [MinigameController::class, 'submitScore'])->name('submit-score');
        Route::get('/results', [MinigameController::class, 'results'])->name('results');
    });

    // Advertisements management
    Route::middleware(['can:sell-advertisements'])->group(function () {
        Route::get('/advertisements/import', [AdvertisementController::class, 'import'])->name('advertisements.import');
        Route::post('/advertisements/import', [AdvertisementController::class, 'processImport'])->name('advertisements.import.process');
        Route::get('/advertisements/create', [AdvertisementController::class, 'create'])->name('advertisements.create');
        Route::post('/advertisements', [AdvertisementController::class, 'store'])->name('advertisements.store');
    });

    Route::post('/advertisements/{advertisement}/purchase', [SalesAndPurchaseController::class, 'buy_advertisement'])->name('advertisements.purchase');
    Route::post('/advertisements/{advertisement}/rent', [SalesAndPurchaseController::class, 'rent_advertisement'])->name('advertisements.rent');
    Route::get('/advertisements/{advertisement}/blocked-dates', [SalesAndPurchaseController::class, 'getBlockedDates'])->name('advertisements.blocked-dates');
    Route::post('/advertisements/{advertisement}/bid', [SalesAndPurchaseController::class, 'bid_advertisement'])->name('advertisements.bid');
    Route::get('/advertisements/{advertisement}/review', [ReviewController::class, 'createAdvertisementReview'])->name('advertisements.review');
    Route::post('/advertisements/{advertisement}/review', [ReviewController::class, 'storeAdvertisementReview'])->name('advertisements.review.store');
    
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::get('/users/{user}/review', [ReviewController::class, 'createUserReview'])->name('users.review');
    Route::post('/users/{user}/review', [ReviewController::class, 'storeUserReview'])->name('users.review.store');

    // Favorites route    
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/advertisements/{advertisement}/favorite', [FavoriteController::class, 'toggle'])->name('advertisements.favorite');

    // Purchases route
    Route::get('/purchases', [SalesAndPurchaseController::class, 'purchases'])->name('purchases.index');
    Route::get('/purchases/calendar', [SalesAndPurchaseController::class, 'purchasesCalendar'])->name('purchases.calendar');
    Route::get('/sales', [SalesAndPurchaseController::class, 'sales'])->name('sales.index');
    Route::get('/sales/calendar', [SalesAndPurchaseController::class, 'salesCalendar'])->name('sales.calendar');
});

// General advertisement routes (should be last)
Route::get('/advertisements', [AdvertisementController::class, 'index'])->name('advertisements.index');
Route::get('/advertisements/{advertisement}', [AdvertisementController::class, 'show'])->name('advertisements.show');

Route::match(['get', 'post'], '/setLocale', function (Request $request) {
    $locale = $request->input('locale');
    if (in_array($locale, ['en', 'nl'])) {
        Session::put('locale', $locale);
    }
    return back();
})->name('setLocale');

// Business routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/business/settings', [BusinessController::class, 'settings'])->name('business.settings');
    Route::put('/business/settings', [BusinessController::class, 'updateSettings'])->name('business.settings.update');
    Route::put('/business/theme', [BusinessController::class, 'updateTheme'])->name('business.theme.update');
    Route::put('/business/domain', [BusinessController::class, 'updateDomain'])->name('business.domain.update');
    
    // Component management routes
    Route::get('/business/edit', [BusinessController::class, 'editComponents'])->name('business.components.edit');
    Route::post('/business/components/add', [BusinessController::class, 'addComponent'])->name('business.components.add');
    Route::post('/business/components/reorder', [BusinessController::class, 'reorderComponents'])->name('business.components.reorder');
    Route::delete('/business/components/{pivotId}', [BusinessController::class, 'deleteComponent'])->name('business.components.delete');
    Route::put('/business/components/{pivotId}', [BusinessController::class, 'updateComponent'])->name('business.components.update');
});

// Public business landing page routes
Route::get('/business/{customUrl}', [BusinessController::class, 'showByCustomUrl'])->name('business.show');

require __DIR__ . '/auth.php';
