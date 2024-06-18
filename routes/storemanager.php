<?php

use App\Http\Controllers\StoreManagerController;
use App\Http\Controllers\StoreManagerProductController;
use App\Http\Controllers\StoreManagerCategoryController;
use App\Http\Controllers\StoreManagerPromoController;
use App\Http\Controllers\StoreManagerOrderController;
use Illuminate\Support\Facades\Route;

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
    
    Route::get('/orders', [StoreManagerOrderController::class, 'index'])->name('storemanager.orders.index');
    Route::get('/orders/{order}', [StoreManagerOrderController::class, 'show'])->name('storemanager.orders.show');
    Route::post('/orders/{order}/verifyPayment', [StoreManagerOrderController::class, 'verifyPayment'])->name('storemanager.orders.verifyPayment');
    Route::post('/orders/{order}/cancel', [StoreManagerOrderController::class, 'cancelOrder'])->name('storemanager.orders.cancel');
    Route::post('/orders/{order}/addTrackingNumber', [StoreManagerOrderController::class, 'addTrackingNumber'])->name('storemanager.orders.addTrackingNumber');
    Route::post('/orders/{order}/complete', [StoreManagerOrderController::class, 'completeOrder'])->name('storemanager.orders.complete');
    Route::post('/orders/{order}/changeStatus', [StoreManagerOrderController::class, 'changeStatus'])->name('storemanager.orders.changeStatus');
    Route::get('/orders/{order}/generateInvoice', [StoreManagerOrderController::class, 'generateInvoice'])->name('storemanager.orders.generateInvoice');


    Route::get('/promos', [StoreManagerPromoController::class, 'index'])->name('storemanager.promos.index');
    Route::get('/promos/create', [StoreManagerPromoController::class, 'create'])->name('storemanager.promos.create');
    Route::post('/promos', [StoreManagerPromoController::class, 'store'])->name('storemanager.promos.store');
    Route::get('/promos/{promo}/edit', [StoreManagerPromoController::class, 'edit'])->name('storemanager.promos.edit');
    Route::put('/promos/{promo}', [StoreManagerPromoController::class, 'update'])->name('storemanager.promos.update');
    Route::delete('/promos/{promo}', [StoreManagerPromoController::class, 'destroy'])->name('storemanager.promos.destroy');
});
