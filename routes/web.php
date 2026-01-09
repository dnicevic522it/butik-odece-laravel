<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SizeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Butik Odeće - Online prodavnica odeće
|
*/

// ==========================================
// JAVNE RUTE - Katalog (dostupno svima)
// ==========================================

// Početna strana
Route::get('/', [ProductController::class, 'index'])->name('home');

// Pregled proizvoda
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

// Pregled kategorija
Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('categories.show');

// ==========================================
// RUTE ZA PRIJAVLJENE KORISNIKE
// ==========================================

Route::middleware(['auth'])->group(function () {

    // Korpa
    Route::get('/cart', [OrderController::class, 'cart'])->name('cart');
    Route::post('/cart/add', [OrderController::class, 'addToCart'])->name('cart.add');
    Route::post('/cart/remove', [OrderController::class, 'removeFromCart'])->name('cart.remove');
    Route::patch('/cart/update', [OrderController::class, 'updateCart'])->name('cart.update');

    // UC-1: Naručivanje proizvoda
    Route::get('/checkout', [OrderController::class, 'create'])->name('checkout');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');

    // Pregled narudžbina
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');

    // UC-3: Otkazivanje narudžbine
    Route::patch('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');

    // Profil korisnika (Laravel Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ==========================================
// ADMIN RUTE
// ==========================================

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // UC-2: Kreiranje i upravljanje proizvodima
    Route::resource('products', ProductController::class);

    // Upravljanje kategorijama
    Route::resource('categories', CategoryController::class);

    // Upravljanje narudžbinama
    Route::resource('orders', OrderController::class)->except(['create', 'store']);

    // Upravljanje korisnicima
    Route::resource('users', UserController::class);

    // Upravljanje veličinama/zalihama
    Route::resource('sizes', SizeController::class);
});

// ==========================================
// AUTENTIFIKACIJA (Laravel Breeze)
// ==========================================

require __DIR__.'/auth.php';
