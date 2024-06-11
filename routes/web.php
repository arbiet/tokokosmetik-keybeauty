<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\StoreManagerController;
use App\Http\Controllers\StoreManagerProductController;
use App\Http\Controllers\StoreManagerCategoryController;
use App\Http\Controllers\StoreManagerPromoController;
use App\Http\Controllers\StoreManagerOrderController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomerProductController;
use App\Http\Controllers\CustomerCartController;
use App\Http\Controllers\CustomerPaymentController;
use App\Http\Controllers\CustomerHistoryController;
use App\Http\Controllers\CustomerPromoController;
use App\Http\Controllers\WelcomeController;

Route::get('/', [WelcomeController::class, 'index'])->name('welcome');
Route::get('/product/{product:slug}', [WelcomeController::class, 'show'])->name('welcome.product.show');

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
            return view('dashboard');
        }
    })->name('dashboard');

    Route::get('/profile/address', [ProfileController::class, 'editAddress'])->name('profile.address');
    Route::patch('/profile/address', [ProfileController::class, 'updateAddress'])->name('profile.address.update');

    Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->group(function () {
        Route::get('/dashboard',[AdminController::class,'index'])->name('admin.dashboard.index');
        Route::get('/user',[AdminController::class,'index'])->name('admin.users.index');
        Route::get('/settings',[AdminController::class,'index'])->name('admin.settings.index');
        Route::get('/reports',[AdminController::class,'index'])->name('admin.reports.index');
    });

    Route::middleware(['auth', 'verified', 'storemanager'])->prefix('storemanager')->group(function () {
        Route::get('/dashboard', [StoreManagerController::class, 'index'])->name('storemanager.dashboard.index');
        Route::get('/products', [StoreManagerProductController::class, 'index'])->name('storemanager.products.index');
        Route::get('/products/create', [StoreManagerProductController::class, 'createProduct'])->name('storemanager.products.create'); 
        Route::post('/products', [StoreManagerProductController::class, 'storeProduct'])->name('storemanager.products.store');
        Route::get('/products/{product}', [StoreManagerProductController::class, 'detailProduct'])->name('storemanager.products.detail');
        Route::post('/products/{product}/addStock', [StoreManagerProductController::class, 'addStock'])->name('storemanager.products.addStock');
        Route::get('/products/{product:id}/edit', [StoreManagerProductController::class, 'editProduct'])->name('storemanager.products.edit');
        Route::put('/products/{product:id}', [StoreManagerProductController::class, 'updateProduct'])->name('storemanager.products.update'); 
        Route::delete('/products/{product:id}', [StoreManagerProductController::class, 'destroyProduct'])->name('storemanager.products.destroy');
        Route::get('/categories', [StoreManagerCategoryController::class, 'index'])->name('storemanager.categories.index');
        Route::get('/categories/create', [StoreManagerCategoryController::class, 'create'])->name('storemanager.categories.create');
        Route::post('/categories', [StoreManagerCategoryController::class, 'store'])->name('storemanager.categories.store');
        Route::get('/categories/{category}/edit', [StoreManagerCategoryController::class, 'edit'])->name('storemanager.categories.edit');
        Route::put('/categories/{category}', [StoreManagerCategoryController::class, 'update'])->name('storemanager.categories.update');
        Route::delete('/categories/{category}', [StoreManagerCategoryController::class, 'destroy'])->name('storemanager.categories.destroy');
        Route::get('/orders', [AdminController::class, 'index'])->name('storemanager.orders.index');

        Route::get('/promos', [StoreManagerPromoController::class, 'index'])->name('storemanager.promos.index');
        Route::get('/promos/create', [StoreManagerPromoController::class, 'create'])->name('storemanager.promos.create');
        Route::post('/promos', [StoreManagerPromoController::class, 'store'])->name('storemanager.promos.store');
        Route::get('/promos/{promo}/edit', [StoreManagerPromoController::class, 'edit'])->name('storemanager.promos.edit');
        Route::put('/promos/{promo}', [StoreManagerPromoController::class, 'update'])->name('storemanager.promos.update');
        Route::delete('/promos/{promo}', [StoreManagerPromoController::class, 'destroy'])->name('storemanager.promos.destroy');

        Route::get('/orders', [StoreManagerOrderController::class, 'index'])->name('storemanager.orders.index');
        Route::get('/orders/{order}', [StoreManagerOrderController::class, 'show'])->name('storemanager.orders.show');
        Route::get('/orders/{order}/verifyPayment', [StoreManagerOrderController::class, 'verifyPayment'])->name('storemanager.orders.verifyPayment');
        Route::get('/orders/{order}/cancel', [StoreManagerOrderController::class, 'cancelOrder'])->name('storemanager.orders.cancel');
        Route::post('/orders/{order}/addTrackingNumber', [StoreManagerOrderController::class, 'addTrackingNumber'])->name('storemanager.orders.addTrackingNumber');
        Route::get('/orders/{order}/complete', [StoreManagerOrderController::class, 'completeOrder'])->name('storemanager.orders.complete');
    });
    
    
    Route::middleware(['auth', 'verified', 'customer'])->prefix('customer')->group(function () {
        Route::get('/dashboard',[CustomerController::class,'index'])->name('customer.dashboard.index');
        Route::get('/products',[CustomerProductController::class,'index'])->name('customer.products.index');
        Route::get('/carts', [CustomerCartController::class, 'index'])->name('customer.carts.index');
        Route::post('/cart/add/{product}', [CustomerCartController::class, 'addToCart'])->name('customer.carts.add'); // Ubah nama rute dan URI
        Route::delete('/carts/{cart}', [CustomerCartController::class, 'destroy'])->name('customer.carts.destroy');
        Route::patch('/cart/update/{cart}', [CustomerCartController::class, 'updateQuantity'])->name('customer.carts.update');
        Route::delete('/cart/destroy/{cart}', [CustomerCartController::class, 'destroy'])->name('customer.carts.destroy');
        Route::post('/cart/checkout', [CustomerCartController::class, 'checkout'])->name('customer.carts.checkout');
        Route::get('/cart/checkout/invoice', [CustomerCartController::class, 'invoice'])->name('customer.carts.invoice');
        Route::post('/promo/check', [CustomerPromoController::class, 'checkPromo'])->name('customer.promo.check');
        Route::get('/payments',[CustomerPaymentController::class,'index'])->name('customer.payments.index');
        Route::get('/history',[CustomerHistoryController::class,'index'])->name('customer.history.index');
    });
    
});