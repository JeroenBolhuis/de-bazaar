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
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');

// Listing routes
Route::get('/listings', [ListingController::class, 'index'])->name('listings.index');
Route::get('/listings/{listing}', [ListingController::class, 'show'])->name('listings.show');

// Rental routes
Route::get('/rentals', [RentalController::class, 'index'])->name('rentals.index');
Route::get('/rentals/{rental}', [RentalController::class, 'show'])->name('rentals.show');


Route::post('/rentals/{advertisement}/reviews', [ReviewController::class, 'store'])->middleware('auth')->name('reviews.store');

// Authenticated routes
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    // Auction routes
    Route::get('/dashboard/auctions/calendar', [SalesAndPurchaseController::class, 'auctionCalendar'])->name('auctions.calendar');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Advertisements management
    Route::middleware(['can:sell-advertisements'])->group(function () {
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

    // Business settings (for business users)
    Route::middleware(['can:manage-business'])->group(function () {
        Route::get('/business/settings', [BusinessController::class, 'settings'])->name('business.settings');
        Route::put('/business/settings', [BusinessController::class, 'updateSettings'])->name('business.settings.update');
        Route::post('/business/theme', [BusinessController::class, 'updateTheme'])->name('business.theme.update');
        Route::post('/business/domain', [BusinessController::class, 'updateDomain'])->name('business.domain.update');
    });

    // Favorites route    
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/advertisements/{advertisement}/favorite', [FavoriteController::class, 'toggle'])->name('advertisements.favorite');

    // Purchases route
    Route::get('/purchases', [SalesAndPurchaseController::class, 'purchases'])->name('purchases.index');
    Route::get('/purchases/calendar', [SalesAndPurchaseController::class, 'purchasesCalendar'])->name('purchases.calendar');
    Route::get('/sales', [SalesAndPurchaseController::class, 'sales'])->name('sales.index');
    Route::get('/sales/calendar', [SalesAndPurchaseController::class, 'salesCalendar'])->name('sales.calendar');
});

Route::get('/advertisements', [AdvertisementController::class, 'index'])->name('advertisements.index');
Route::get('/advertisements/{advertisement}', [AdvertisementController::class, 'show'])->name('advertisements.show');

Route::match(['get', 'post'], '/setLocale', function (Request $request) {
    $locale = $request->input('locale');
    if (in_array($locale, ['en', 'nl'])) {
        Session::put('locale', $locale);
    }
    return back();
})->name('setLocale');

require __DIR__ . '/auth.php';
