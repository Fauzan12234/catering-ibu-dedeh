<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\RawMaterialController;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ReportController;

// --- HALAMAN DEPAN (GUEST) ---
Route::get('/', [FrontController::class, 'index'])->name('home');
Route::get('/menu', [FrontController::class, 'menu'])->name('menu');
Route::get('/menu/{id}', [FrontController::class, 'menuDetail'])->name('menu.detail'); // Rute baru
Route::get('/galeri', [FrontController::class, 'galeri'])->name('galeri');
Route::get('/kontak', [FrontController::class, 'kontak'])->name('kontak');

// --- OTENTIKASI ---
// Tambahkan rute GET login ini agar Laravel tidak error saat logout
Route::get('/login', [AuthController::class, 'showLogin'])->name('login'); 
Route::post('/login', [AuthController::class, 'processLogin'])->name('login.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// --- PANEL ADMIN (MIDDLEWARE AUTH) ---
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard Utama
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Modul Kelola Etalase (Visual Website)
    Route::get('/konten', [ContentController::class, 'index'])->name('konten');
    Route::post('/konten/menu/{id}', [ContentController::class, 'updateMenuPhoto'])->name('konten.menu.update');
    Route::post('/konten/gallery', [ContentController::class, 'storeGallery'])->name('konten.gallery.store');
    Route::delete('/konten/gallery/{id}', [ContentController::class, 'destroyGallery'])->name('konten.gallery.destroy');
    
    // Modul Menu & Resep (BOM)
    Route::get('/menus', [MenuController::class, 'index'])->name('menus.index');
    Route::get('/menus/create', [MenuController::class, 'create'])->name('menus.create');
    Route::post('/menus', [MenuController::class, 'store'])->name('menus.store');
    Route::get('/menus/{id}/edit', [MenuController::class, 'edit'])->name('menus.edit');
    Route::put('/menus/{id}', [MenuController::class, 'update'])->name('menus.update');
    Route::delete('/menus/{id}', [MenuController::class, 'destroy'])->name('menus.destroy');

    // Modul Manajemen Pesanan & Invoice
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::patch('/orders/{id}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::delete('/orders/{id}', [OrderController::class, 'destroy'])->name('orders.destroy');

    // Modul Belanja & Pembelian (Hari H)
    Route::get('/shopping-list', [PurchaseController::class, 'shoppingList'])->name('purchases.shopping');
    Route::get('/purchases/create', [PurchaseController::class, 'create'])->name('purchases.create');
    Route::post('/purchases', [PurchaseController::class, 'store'])->name('purchases.store');

    // Modul Gudang & Supplier
    Route::get('/suppliers', [SupplierController::class, 'index'])->name('suppliers.index');
    Route::post('/suppliers', [SupplierController::class, 'store'])->name('suppliers.store');
    Route::delete('/suppliers/{id}', [SupplierController::class, 'destroy'])->name('suppliers.destroy');

    Route::get('/materials', [RawMaterialController::class, 'index'])->name('materials.index');
    Route::post('/materials', [RawMaterialController::class, 'store'])->name('materials.store');
    Route::delete('/materials/{id}', [RawMaterialController::class, 'destroy'])->name('materials.destroy');

    // Laporan Finansial
    Route::get('/laporan/laba-rugi', [ReportController::class, 'profitLoss'])->name('reports.profit_loss');
});