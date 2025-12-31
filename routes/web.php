<?php

use App\Http\Controllers\Cashier\POSController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ProductImportController;
use App\Http\Controllers\Admin\RoleController as AdminRoleController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Models\Role;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => view('welcome'));

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin-only routes
    Route::prefix('admin')
        ->name('admin.')
        ->middleware('role:' . Role::ADMIN)
        ->group(function () {
            Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
            Route::post('/users', [AdminUserController::class, 'store'])->name('users.store');
            Route::get('/roles', [AdminRoleController::class, 'index'])->name('roles.index');
            Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
        });

    // Admin & Manager routes (products)
    Route::prefix('admin')
        ->name('admin.')
        ->middleware('role:' . Role::ADMIN . ',' . Role::STORE_MANAGER)
        ->group(function () {
            Route::get('/products', [AdminProductController::class, 'index'])->name('products.index');
            Route::post('/products', [AdminProductController::class, 'store'])->name('products.store');
            Route::get('/products/{product}/edit', [AdminProductController::class, 'edit'])->name('products.edit');
            Route::put('/products/{product}', [AdminProductController::class, 'update'])->name('products.update');
            Route::delete('/products/{product}', [AdminProductController::class, 'destroy'])->name('products.destroy');
            Route::get('/products/import', [ProductImportController::class, 'index'])->name('products.import.form');
            Route::post('/products/import', [ProductImportController::class, 'import'])->name('products.import');
            Route::get('/products/import/template', [ProductImportController::class, 'downloadTemplate'])->name('products.import.template');
        });

    // Manager routes
    Route::prefix('manager')
        ->name('manager.')
        ->middleware('role:' . Role::ADMIN . ',' . Role::STORE_MANAGER)
        ->group(function () {
            Route::get('/products', [AdminProductController::class, 'index'])->name('products.index');
            Route::post('/products', [AdminProductController::class, 'store'])->name('products.store');
            Route::get('/products/{product}/edit', [AdminProductController::class, 'edit'])->name('products.edit');
            Route::put('/products/{product}', [AdminProductController::class, 'update'])->name('products.update');
            Route::delete('/products/{product}', [AdminProductController::class, 'destroy'])->name('products.destroy');
            Route::get('/products/export', [AdminProductController::class, 'export'])->name('products.export');
            Route::get('/products/import', [ProductImportController::class, 'index'])->name('products.import.form');
            Route::post('/products/import', [ProductImportController::class, 'import'])->name('products.import');
            Route::get('/products/import/template', [ProductImportController::class, 'downloadTemplate'])->name('products.import.template');
        });

    // Cashier routes
    Route::prefix('cashier')
        ->name('cashier.')
        ->middleware('role:' . Role::CASHIER)
        ->group(function () {
            Route::get('/pos', [POSController::class, 'index'])->name('pos');
            Route::post('/pos', [POSController::class, 'store'])->name('pos.store');
            Route::get('/transactions', [POSController::class, 'transactions'])->name('transactions');
            Route::get('/receipt/{order}', [POSController::class, 'receipt'])->name('receipt');
        });
});

require __DIR__ . '/auth.php';
