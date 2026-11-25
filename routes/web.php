<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/about', function () {
    return 'POS Barokah Mart adalah aplikasi kasir toko kelontong '
         . 'untuk mengelola produk, transaksi, stok, dan laporan '
         . 'penjualan harian.';
})->name('about');

Route::get('/login', [LoginController::class, 'create'])
    ->middleware('guest')
    ->name('login');
Route::post('/login', [LoginController::class, 'store'])
    ->middleware('guest')
    ->name('login.store');
Route::post('/logout', [LoginController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

// Hanya admin
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('categories', CategoryController::class);
    Route::resource('products', ProductController::class);
    Route::get('/reports/sales', [ReportController::class, 'sales'])
        ->name('report.sales');
    Route::resource('users', UserController::class)
        ->only(['index', 'create', 'store', 'destroy']);
});

// Admin dan kasir
Route::middleware(['auth', 'role:admin,kasir'])->group(function () {
    Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
    Route::post('/pos', [PosController::class, 'store'])->name('pos.store');

    // Latihan Bab 5: riwayat transaksi kasir (isi dikerjakan di Bab 12)
    Route::get('/pos/history', [PosController::class, 'history'])
        ->name('pos.history');
    Route::get('/pos/{transaction}/struk', [PosController::class, 'struk'])
        ->name('pos.struk');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/reports/sales/export', [\App\Http\Controllers\ReportController::class, 'export'])->name('report.export');
});

// ---- Bab 14: Supplier & Pembelian Stok (khusus admin) ----
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('suppliers', \App\Http\Controllers\SupplierController::class)
        ->except('show');
    Route::resource('purchases', \App\Http\Controllers\PurchaseOrderController::class)
        ->except(['edit', 'update']);
});

// ---- Bab 14: Struk thermal 58mm (admin & kasir) ----
Route::middleware(['auth', 'role:admin,kasir'])->group(function () {
    Route::get('/pos/{transaction}/struk-thermal',
        [\App\Http\Controllers\PosController::class, 'strukThermal'])
        ->name('pos.struk.thermal');
});
