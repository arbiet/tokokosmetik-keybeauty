<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\StoreManagerController;
use App\Http\Controllers\CostumerController;

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
});

require __DIR__.'/auth.php';


Route::get('admin/dashboard',[AdminController::class,'index'])->middleware(['auth','admin']);
Route::get('storemanager/dashboard',[StoreManagerController::class,'index'])->middleware(['auth','storemanager']);
Route::get('customer/dashboard',[CostumerController::class,'index'])->middleware(['auth','costumer']);