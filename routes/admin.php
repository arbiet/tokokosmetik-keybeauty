<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard',[AdminController::class,'index'])->name('admin.dashboard.index');
    Route::get('/user',[AdminController::class,'index'])->name('admin.users.index');
    Route::get('/settings',[AdminController::class,'index'])->name('admin.settings.index');
    Route::get('/reports',[AdminController::class,'index'])->name('admin.reports.index');
});
