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
Route::get('/siparis-takip', [PageController::class, 'track'])->name('orders.track');
Route::post('/siparis-takip', [PageController::class, 'trackLookup'])->name('orders.track.lookup');
Route::get('/yasal/{slug}', [PageController::class, 'legal'])->name('legal');

// Sitemap (1 saat önbellekli)
Route::get('/sitemap.xml', function () {
    $xml = cache()->remember('sitemap', 3600, function () {
        $urls = [
            ['loc' => route('home'), 'priority' => '1.0'],
            ['loc' => route('products.index'), 'priority' => '0.9'],
            ['loc' => route('products.campaigns'), 'priority' => '0.8'],
            ['loc' => route('about'), 'priority' => '0.5'],
            ['loc' => route('contact'), 'priority' => '0.5'],
            ['loc' => route('orders.track'), 'priority' => '0.4'],
        ];

        foreach (\App\Models\Category::active()->get() as $category) {
            $urls[] = ['loc' => route('products.index', ['kategori' => $category->slug]), 'priority' => '0.7'];
        }

        foreach (\App\Models\Product::active()->get() as $product) {
            $urls[] = [
                'loc' => route('products.show', $product),
                'priority' => '0.8',
                'lastmod' => $product->updated_at?->toAtomString(),
            ];
        }

        $out = '<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        foreach ($urls as $url) {
            $out .= '<url><loc>'.e($url['loc']).'</loc>';
            $out .= isset($url['lastmod']) ? '<lastmod>'.$url['lastmod'].'</lastmod>' : '';
            $out .= '<priority>'.$url['priority'].'</priority></url>';
        }

        return $out.'</urlset>';
    });

    return response($xml, 200)->header('Content-Type', 'application/xml');
})->name('sitemap');

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

// Google ile giriş
Route::get('/giris/google', [\App\Http\Controllers\GoogleAuthController::class, 'redirect'])->name('google.redirect');
Route::get('/giris/google/callback', [\App\Http\Controllers\GoogleAuthController::class, 'callback'])->name('google.callback');

Route::middleware('auth')->group(function () {
    Route::post('/cikis', [AuthController::class, 'logout'])->name('logout');
    Route::get('/hesabim', [AccountController::class, 'index'])->name('account.index');
    Route::patch('/hesabim/profil', [AccountController::class, 'updateProfile'])->name('account.profile');
    Route::patch('/hesabim/sifre', [AccountController::class, 'updatePassword'])->name('account.password');
    Route::get('/hesabim/siparisler', [AccountController::class, 'orders'])->name('account.orders');
    Route::get('/hesabim/siparis/{orderNumber}', [AccountController::class, 'orderDetail'])->name('account.order-detail');
});
