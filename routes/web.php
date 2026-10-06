<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuditLogController;

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
    Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
    Route::post('/pos/add/{product}', [PosController::class, 'add'])->name('pos.add');
    Route::post('/pos/cart/update', [PosController::class, 'updateCart'])->name('pos.cart.update');

    // RECEIPTS
    Route::get('/receipts/{sale}', [ReceiptController::class, 'show'])->name('receipts.show');
    Route::get('/receipts/{sale}/download', [ReceiptController::class, 'download'])->name('receipts.download');

    // INVENTORY
    Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');
    Route::get('/inventory/create', [InventoryController::class, 'create'])->name('inventory.create');
    Route::post('/inventory', [InventoryController::class, 'store'])->name('inventory.store');
    Route::get('/inventory/{product}/edit', [InventoryController::class, 'edit'])->name('inventory.edit');
    Route::put('/inventory/{product}', [InventoryController::class, 'update'])->name('inventory.update');

    // SALES TRACKING
    Route::get('/sales', [SalesController::class, 'index'])->name('sales.index');
    Route::patch('/sales/{sale}/complete', [SalesController::class, 'markCompleted'])->name('sales.complete');

    // REPORTS (SHARED - ADMIN & STAFF)
    Route::get('/reports', function () {
        return view('reports.index');
    })->name('reports.index');

    // ADMIN ONLY ROUTES
    Route::group(['middleware' => function ($request, $next) {
        if (! auth()->user()->isAdmin()) {
            abort(403);
        }
        return $next($request);
    }], function () {

        // USER MANAGEMENT
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::patch('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');

        // AUDIT LOG
      Route::get('/audit-log', [AuditLogController::class, 'index'])->name('audit-log.index');
    });

});