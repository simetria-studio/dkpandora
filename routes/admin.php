<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SettingController;

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Products Management
    Route::resource('products', AdminProductController::class);
    Route::post('products/bulk-action', [AdminProductController::class, 'bulkAction'])->name('products.bulk-action');

    // Orders Management
    Route::resource('orders', AdminOrderController::class);
    Route::post('orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.update-status');

    // Users Management
    Route::resource('users', UserController::class);

    // Reports
    Route::get('reports/sales', [DashboardController::class, 'salesReport'])->name('reports.sales');
    Route::get('reports/products', [DashboardController::class, 'productsReport'])->name('reports.products');

    // Settings
    Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('settings', [SettingController::class, 'update'])->name('settings.update');
    Route::post('settings/gold', [SettingController::class, 'updateGold'])->name('settings.update-gold');
});
