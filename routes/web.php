<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\AuctionController;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\RentalController;
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

// Auction routes
Route::get('/auctions', [AuctionController::class, 'index'])->name('auctions.index');
Route::get('/auctions/{auction}', [AuctionController::class, 'show'])->name('auctions.show');



Route::post('/rentals/{advertisement}/reviews', [ReviewController::class, 'store'])->middleware('auth')->name('reviews.store');

// Authenticated routes
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Advertisements management
    Route::get('/advertisements/create', [AdvertisementController::class, 'create'])->name('advertisements.create');
    Route::post('/advertisements', [AdvertisementController::class, 'store'])->name('advertisements.store');
    Route::post('/advertisements/{advertisement}/purchase', [PurchaseController::class, 'store'])->name('advertisements.purchase');
    Route::get('/advertisements/{advertisement}/review', [ReviewController::class, 'createAdvertisementReview'])->name('advertisements.review');
    Route::post('/advertisements/{advertisement}/review', [ReviewController::class, 'storeAdvertisementReview'])->name('advertisements.review.store');
    
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::get('/users/{user}/review', [ReviewController::class, 'createUserReview'])->name('users.review');
    Route::post('/users/{user}/review', [ReviewController::class, 'storeUserReview'])->name('users.review.store');
    

    // Listings management
    Route::get('/listings/create', [ListingController::class, 'create'])->name('listings.create');
    Route::post('/listings', [ListingController::class, 'store'])->name('listings.store');
    Route::get('/listings/{listing}/edit', [ListingController::class, 'edit'])->name('listings.edit');
    Route::put('/listings/{listing}', [ListingController::class, 'update'])->name('listings.update');
    Route::delete('/listings/{listing}', [ListingController::class, 'destroy'])->name('listings.destroy');

    // Rentals management
    Route::get('/rentals/create', [RentalController::class, 'create'])->name('rentals.create');
    Route::post('/rentals', [RentalController::class, 'store'])->name('rentals.store');
    Route::get('/rentals/{rental}/edit', [RentalController::class, 'edit'])->name('rentals.edit');
    Route::put('/rentals/{rental}', [RentalController::class, 'update'])->name('rentals.update');
    Route::delete('/rentals/{rental}', [RentalController::class, 'destroy'])->name('rentals.destroy');

    // Auctions management
    Route::get('/auctions/create', [AuctionController::class, 'create'])->name('auctions.create');
    Route::post('/auctions', [AuctionController::class, 'store'])->name('auctions.store');
    Route::get('/auctions/{auction}/edit', [AuctionController::class, 'edit'])->name('auctions.edit');
    Route::put('/auctions/{auction}', [AuctionController::class, 'update'])->name('auctions.update');
    Route::delete('/auctions/{auction}', [AuctionController::class, 'destroy'])->name('auctions.destroy');

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
    Route::get('/purchases', [PurchaseController::class, 'index'])->name('purchases.index');
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
