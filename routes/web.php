<?php

use App\Http\Controllers\Admin\BannerController as AdminBannerController;
use App\Http\Controllers\Admin\BrandController as AdminBrandController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\CouponController as AdminCouponController;
use App\Http\Controllers\Admin\CustomerController as AdminCustomerController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\LiveChatController as AdminLiveChatController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\SiteSettingController as AdminSiteSettingController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CustomerDashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SettingAssetController;
use App\Http\Controllers\LiveChatController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/settings-assets/{path}', [SettingAssetController::class, 'show'])
    ->where('path', '.*')
    ->name('settings.assets.show');
Route::get('/product-images/{path}', [SettingAssetController::class, 'productImage'])
    ->where('path', '.*')
    ->name('product-images.show');
Route::get('/category-images/{path}', [SettingAssetController::class, 'categoryImage'])
    ->where('path', '.*')
    ->name('category-images.show');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product:slug}', [ProductController::class, 'show'])->name('products.show');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/live-chat', [LiveChatController::class, 'store'])->name('live-chat.store');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/{product}', [CartController::class, 'store'])->name('cart.store');
Route::post('/buy-now/{product}', [CartController::class, 'buyNow'])->name('cart.buy-now');
Route::patch('/cart/{product}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{product}', [CartController::class, 'destroy'])->name('cart.destroy');
Route::get('/checkout', [CheckoutController::class, 'create'])->name('checkout.create');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/orders/{order}/confirmation', [CheckoutController::class, 'confirmation'])->name('orders.confirmation');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [CustomerDashboardController::class, 'index'])->name('customer.dashboard');
    Route::get('/dashboard/orders', [CustomerDashboardController::class, 'orders'])->name('customer.orders');
    Route::get('/dashboard/orders/{order}', [CustomerDashboardController::class, 'showOrder'])->name('customer.orders.show');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('products', AdminProductController::class)->except('show');
    Route::resource('categories', AdminCategoryController::class)->except('show');
    Route::resource('brands', AdminBrandController::class)->except('show');
    Route::resource('coupons', AdminCouponController::class)->except('show');
    Route::resource('banners', AdminBannerController::class)->except('show');
    Route::resource('orders', AdminOrderController::class)->only(['index', 'show', 'update']);
    Route::resource('customers', AdminCustomerController::class)->only(['index', 'show']);
    Route::get('live-chat', [AdminLiveChatController::class, 'index'])->name('live-chat.index');
    Route::patch('live-chat/{liveChatMessage}', [AdminLiveChatController::class, 'update'])->name('live-chat.update');
    Route::delete('live-chat/{liveChatMessage}', [AdminLiveChatController::class, 'destroy'])->name('live-chat.destroy');
    Route::get('settings', [AdminSiteSettingController::class, 'edit'])->name('settings.edit');
    Route::put('settings', [AdminSiteSettingController::class, 'update'])->name('settings.update');
});

