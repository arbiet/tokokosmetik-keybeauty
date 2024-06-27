<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WelcomeController;

Route::get('/', [WelcomeController::class, 'index'])->name('welcome');
Route::get('/product/{product:slug}', [WelcomeController::class, 'show'])->name('welcome.product.show');
Route::get('/email/verify-notice', function () {
    return view('auth.verify-notice');
})->name('verification.notice');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        $user = Auth::user();

        // Cek peran pengguna dan arahkan ke dashboard yang sesuai
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard.index');
        } elseif ($user->isStoreManager()) {
            return redirect()->route('storemanager.dashboard.index');
        } elseif ($user->isCustomer()) {
            return redirect()->route('customer.dashboard.index');
        } else {
            return view('login');
        }
    })->name('dashboard');

    Route::get('/profile/address', [ProfileController::class, 'editAddress'])->name('profile.address');
    Route::patch('/profile/address', [ProfileController::class, 'updateAddress'])->name('profile.address.update');
    Route::get('/profile/cities/{province}', [ProfileController::class, 'getCities'])->name('profile.cities');
    Route::get('/profile/postal-code/{city}', [ProfileController::class, 'getPostalCode'])->name('profile.postal-code');
    Route::post('/calculate-shipping-cost', [CustomerCartController::class, 'calculateShippingCost'])->name('calculateShippingCost');
    Route::get('/promos', [CustomerPromoController::class, 'fetchPromos'])->name('customer.promo.list');
});

require __DIR__.'/admin.php';
require __DIR__.'/storemanager.php';
require __DIR__.'/customer.php';
