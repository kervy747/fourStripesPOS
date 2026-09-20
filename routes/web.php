<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\InventoryController;

// ROOT REDIRECT
Route::get('/', function () {
    return redirect()->route('login');
});

// GUEST ROUTES (LOGIN)
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.attempt');
});

// AUTHENTICATED ROUTES
Route::middleware('auth')->group(function () {

    // LOGOUT
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // POS
    Route::get('/pos', function () {
        return 'POS page coming soon';
    })->name('pos.index');

    // INVENTORY
    Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');
    Route::get('/inventory/create', [InventoryController::class, 'create'])->name('inventory.create');
    Route::post('/inventory', [InventoryController::class, 'store'])->name('inventory.store');
    Route::get('/inventory/{product}/edit', [InventoryController::class, 'edit'])->name('inventory.edit');
    Route::put('/inventory/{product}', [InventoryController::class, 'update'])->name('inventory.update');

    // SALES TRACKING
    Route::get('/sales', function () {
        return 'Sales Tracking page coming soon';
    })->name('sales.index');

    // REPORTS (SHARED - ADMIN & STAFF)
    Route::get('/reports', function () {
        return 'Reports page coming soon';
    })->name('reports.index');

    // ADMIN ONLY ROUTES
    Route::get('/users', function () {
        return 'User Management page coming soon';
    })->name('users.index');

    Route::get('/audit-log', function () {
        return 'Audit Log page coming soon';
    })->name('audit-log.index');

});