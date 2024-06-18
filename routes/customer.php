<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomerProductController;
use App\Http\Controllers\CustomerCartController;
use App\Http\Controllers\CustomerPaymentController;
use App\Http\Controllers\CustomerHistoryController;
use App\Http\Controllers\CustomerPromoController;
use App\Http\Controllers\CustomerOrderController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'customer'])->prefix('customer')->group(function () {
    Route::get('/dashboard', [CustomerController::class, 'dashboard'])->name('customer.dashboard.index');
    Route::get('/products', [CustomerProductController::class, 'index'])->name('customer.products.index');
    Route::get('/carts', [CustomerCartController::class, 'index'])->name('customer.carts.index');
    Route::post('/cart/add/{product}', [CustomerCartController::class, 'addToCart'])->name('customer.carts.add'); 
    Route::delete('/carts/{cart}', [CustomerCartController::class, 'destroy'])->name('customer.carts.destroy');
    Route::patch('/cart/update/{cart}', [CustomerCartController::class, 'updateQuantity'])->name('customer.carts.update');
    Route::delete('/cart/destroy/{cart}', [CustomerCartController::class, 'destroy'])->name('customer.carts.destroy');
    Route::post('/cart/checkout', [CustomerCartController::class, 'checkout'])->name('customer.carts.checkout');
    Route::get('/cart/checkout/invoice', [CustomerCartController::class, 'invoice'])->name('customer.carts.invoice');
    Route::post('/promo/check', [CustomerPromoController::class, 'checkPromo'])->name('customer.promo.check');
    Route::get('/payments', [CustomerPaymentController::class, 'index'])->name('customer.payments.index');
    Route::get('/history', [CustomerHistoryController::class, 'index'])->name('customer.history.index');
    Route::post('/orders', [CustomerOrderController::class, 'store'])->name('customer.orders.store');
    Route::get('/orders', [CustomerOrderController::class, 'index'])->name('customer.orders.index');
    Route::get('/orders/{order}', [CustomerOrderController::class, 'show'])->name('customer.orders.show');
    Route::post('/orders/{order}/upload-payment-proof', [CustomerOrderController::class, 'uploadPaymentProof'])->name('customer.orders.uploadPaymentProof');
    Route::get('customer/orders/{order}/invoice', [CustomerOrderController::class, 'generateInvoice'])->name('customer.orders.invoice');
    Route::post('/customer/orders/{order}/complete', [CustomerOrderController::class, 'complete'])->name('customer.orders.complete');
});
