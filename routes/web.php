<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

// Anasayfa & statik sayfalar
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/hakkimizda', [PageController::class, 'about'])->name('about');
Route::get('/iletisim', [PageController::class, 'contact'])->name('contact');
Route::post('/iletisim', [PageController::class, 'contactStore'])->name('contact.store');

// Ürünler
Route::get('/urunler', [ProductController::class, 'index'])->name('products.index');
Route::get('/firsatlar', [ProductController::class, 'campaigns'])->name('products.campaigns');
Route::get('/urun/{product}', [ProductController::class, 'show'])->name('products.show');

// Sepet
Route::get('/sepet', [CartController::class, 'index'])->name('cart.index');
Route::post('/sepet/ekle/{product}', [CartController::class, 'add'])->name('cart.add');
Route::patch('/sepet/guncelle/{key}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/sepet/sil/{key}', [CartController::class, 'remove'])->name('cart.remove');

// Ödeme
Route::get('/odeme', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/odeme', [CheckoutController::class, 'pay'])->name('checkout.pay');
Route::match(['get', 'post'], '/odeme/callback', [CheckoutController::class, 'callback'])->name('checkout.callback');
Route::get('/odeme/basarili', [CheckoutController::class, 'success'])->name('checkout.success');
Route::get('/odeme/basarisiz', [CheckoutController::class, 'failed'])->name('checkout.failed');

// Üyelik
Route::middleware('guest')->group(function () {
    Route::get('/giris', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/giris', [AuthController::class, 'login'])->name('login.post');
    Route::get('/kayit', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/kayit', [AuthController::class, 'register'])->name('register.post');
});

Route::middleware('auth')->group(function () {
    Route::post('/cikis', [AuthController::class, 'logout'])->name('logout');
    Route::get('/hesabim/siparisler', [AccountController::class, 'orders'])->name('account.orders');
    Route::get('/hesabim/siparis/{orderNumber}', [AccountController::class, 'orderDetail'])->name('account.order-detail');
});
