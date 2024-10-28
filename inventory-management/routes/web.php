<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProfileController;

// Rute untuk login
Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/', [AuthController::class, 'login']);

// Rute untuk logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rute untuk registrasi
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Rute login di luar middleware auth
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

// Grup rute yang memerlukan autentikasi
Route::middleware(['auth'])->group(function () {
    // Rute dashboard
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
    
    // Rute untuk export dan import (pindahkan ke dalam grup auth)
    Route::get('/products/export', [ProductController::class, 'export'])->name('products.export');
    Route::get('/products/import', [ProductController::class, 'importForm'])->name('products.import.form');
    Route::post('/products/import', [ProductController::class, 'import'])->name('products.import');
    
    // Rute produk untuk pengguna
    Route::get('/products/user', [ProductController::class, 'userIndex'])->name('products.user_index');
    Route::get('/products/view', [ProductController::class, 'userView'])->name('products.user_view');
    
    // Rute CRUD untuk produk
    Route::resource('products', ProductController::class);
    
    // Rute untuk pesanan pengguna
    Route::get('/orders/user', [OrderController::class, 'userOrders'])->name('orders.user');
    // Rute CRUD untuk pesanan
    Route::resource('orders', OrderController::class);
    
    // Rute untuk menyimpan pesanan
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::post('/orders/download-pdf', [OrderController::class, 'downloadPdf'])->name('orders.download-pdf');
    
    // Rute CRUD untuk pengguna
    Route::resource('users', UserController::class);
    
    // Rute untuk profil pengguna
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// Rute uji coba dengan middleware auth
Route::get('/test-route', function() {
    return 'Test route works!';
})->middleware('auth');
