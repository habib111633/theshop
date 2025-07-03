<?php

use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:admin|manager|worker'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('categories', CategoryController::class);
        Route::resource('users', UserController::class);
        Route::resource('products', ProductController::class);
        Route::resource('orders', OrderController::class);
        Route::resource('roles', RoleController::class);
        Route::prefix('exports')->name('exports.')->group(function () {
            Route::get('/products', [\App\Http\Controllers\Export\ProductExportController::class, 'export'])->name('products');
            Route::get('/products/queued', [\App\Http\Controllers\Export\ProductExportController::class, 'exportQueued'])->name('products.queued');
        });
        // ...other admin routes
    });
