<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\StoreManagerController;
use App\Http\Controllers\StoreManagerProductController;
use App\Http\Controllers\StoreManagerCategoryController;
use App\Http\Controllers\CostumerController;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

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
    });
    
    
    Route::middleware(['auth', 'verified', 'customer'])->prefix('customer')->group(function () {
        Route::get('/dashboard',[AdminController::class,'index'])->name('customer.dashboard.index');
        Route::get('/products',[AdminController::class,'index'])->name('customer.products.index');
        Route::get('/carts',[AdminController::class,'index'])->name('customer.carts.index');
        Route::get('/payments',[AdminController::class,'index'])->name('customer.payments.index');
        Route::get('/history',[AdminController::class,'index'])->name('customer.history.index');
    });
});