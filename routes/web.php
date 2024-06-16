<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AplyPromController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\FlavourController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductDetailsController;
use App\Http\Controllers\Admin\PromotionController;
use App\Http\Controllers\Admin\UserAdminController;
use App\Http\Controllers\Admin\WeightController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ProductOverviewController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\CartController;
use App\Http\Controllers\User\CheckoutController;
use App\Http\Controllers\User\OrderSummaryController;
use App\Http\Controllers\User\ProductListController;
use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Start User Routes
Route::get('/', [UserController::class, 'index'])->name('user.home');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [UserController::class, 'profile'])->name('user.profile');
    Route::get('/profile/user', [UserController::class, 'profileUserData'])->name('user.profileUserData');
    Route::get('/profile/address', [UserController::class, 'profileAddress'])->name('user.profile.useraddress');
    Route::post('/profile/address/store', [UserController::class, 'store'])->name('user.profile.useraddress.store');
    Route::put('/profile/address/update/{id}', [UserController::class, 'update'])->name('user.profile.useraddress.update');
    Route::delete('/profile/address/delete/{id}', [UserController::class, 'delete'])->name('user.profile.useraddress.delete');

    Route::get('/profile/address/order', [UserController::class, 'profileUserOrders'])->name('user.profile.order');

    Route::get('/profile/address/order/{id}', [UserController::class, 'orderDetails'])->name('user.profile.order.detail');
    
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Add to Cart Routes
Route::prefix('cart')->controller(CartController::class)->group(function () {
    Route::get('view', 'view')->name('cart.view');
    Route::post('store/{product}/{weight}/{flavour}/{quantity}/{price}', 'store')->name('cart.store');
    Route::patch('update/{product}/{weight}/{flavour}', 'update')->name('cart.update');
    Route::delete('delete/{product}/{weight}/{flavour}', 'delete')->name('cart.delete');
    Route::post('applyCoupon', 'applyCoupon')->name('cart.applyCoupon');
});

// Product List and Filter Routes
Route::prefix('products')->controller(ProductListController::class)->group(function () {
    Route::get('/', 'index')->name('products.index');
});

// Peticiones http para obtener datos
Route::get('/api/categories', function () {
    return \App\Models\Category::all();
});

Route::get('/api/brands', function () {
    return \App\Models\Brand::all();
});

Route::get('/api/products', function () {
    return \App\Models\Product::with('promotion', 'product_images', 'stock_products.weight','stock_products.flavour', 'brand', 'category')->inRandomOrder()
    ->take(3)
    ->get();
});

Route::get('/api/weights', function() {
    return \App\Models\Weight::all();
});

Route::get('/api/flavours', function() {
    return \App\Models\Flavour::all();
});

Route::get('/api/products/search', [ProductController::class, 'search']);

// Product Overview Routes
Route::prefix('{slug}')->group(function () {
    Route::get('/', [ProductOverviewController::class, 'index'])->name('product.overview');
});

// Order Summary Routes
Route::middleware('auth')->prefix('order-summary')->group(function () {
    Route::get('/', [OrderSummaryController::class, 'index'])->name('order.index');
});

// Checkout Routes
Route::get('/{total}', [CheckoutController::class, 'index'])->prefix('checkout')->name('checkout.index');
Route::middleware('auth')->prefix('checkout')->group(function () {
   
    Route::post('/success', [CheckoutController::class, 'success'])->name('checkout.success');
    Route::get('/cancel', [CheckoutController::class, 'cancel'])->name('checkout.cancel');
    Route::post('/newAddress',[CheckoutController::class, 'saveNewAddress'])->name('checkout.newAddress');
});

// Start Admin Routes
Route::group(['prefix' => 'admin', 'middleware' => 'redirectAdmin'], function () {
    Route::get('login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('login', [AdminAuthController::class, 'login'])->name('admin.login.post');
    Route::post('logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
    Route::get('register', [RegisteredUserController::class, 'create'])->name('admin.register');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

    // Product Routes
    Route::prefix('products')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('admin.products.index');
        Route::post('/store', [ProductController::class, 'store'])->name('admin.products.store');
        Route::put('/update/{id}', [ProductController::class, 'update'])->name('admin.products.update');
        Route::delete('/delete/{id}', [ProductController::class, 'delete'])->name('admin.products.delete');
        Route::delete('/image/{id}', [ProductController::class, 'deleteImage'])->name('admin.product.image.delete');
        Route::put('/publish/{id}', [ProductController::class, 'publishProduct'])->name('admin.publishProduct');
    });

    // Product Details Routes
    Route::prefix('details/products')->group(function () {
        Route::get('/{id}', [ProductDetailsController::class, 'index'])->name('admin.productDetail.index');
        Route::post('/store', [ProductDetailsController::class, 'store'])->name('admin.productDetail.store');
        Route::put('/update/{id}', [ProductDetailsController::class, 'update'])->name('admin.productDetail.update');
        Route::delete('/destroy/{id}', [ProductDetailsController::class, 'delete'])->name('admin.productDetail.destroy');
    });

    // Category Routes
    Route::prefix('categories')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('admin.categories.index');
        Route::post('/store', [CategoryController::class, 'store'])->name('admin.categories.store');
        Route::put('/update/{id}', [CategoryController::class, 'update'])->name('admin.categories.update');
        Route::delete('/delete/{id}', [CategoryController::class, 'delete'])->name('admin.categories.delete');
    });

    // Brand Routes
    Route::prefix('brands')->group(function () {
        Route::get('/', [BrandController::class, 'index'])->name('admin.brands.index');
        Route::post('/store', [BrandController::class, 'store'])->name('admin.brands.store');
        Route::put('/update/{id}', [BrandController::class, 'update'])->name('admin.brands.update');
        Route::delete('/delete/{id}', [BrandController::class, 'delete'])->name('admin.brands.delete');
    });

    // Flavour Routes
    Route::prefix('flavours')->group(function () {
        Route::get('/', [FlavourController::class, 'index'])->name('admin.flavours.index');
        Route::post('/store', [FlavourController::class, 'store'])->name('admin.flavours.store');
        Route::put('/update/{id}', [FlavourController::class, 'update'])->name('admin.flavours.update');
        Route::delete('/delete/{id}', [FlavourController::class, 'delete'])->name('admin.flavour.delete');
    });

    // Weight Routes
    Route::prefix('weights')->group(function () {
        Route::get('/', [WeightController::class, 'index'])->name('admin.weights.index');
        Route::post('/store', [WeightController::class, 'store'])->name('admin.weights.store');
        Route::put('/update/{id}', [WeightController::class, 'update'])->name('admin.weights.update');
        Route::delete('/delete/{id}', [WeightController::class, 'delete'])->name('admin.weights.delete');
    });

    // Promotion Routes
    Route::prefix('promotions')->group(function () {
        Route::get('/', [PromotionController::class, 'index'])->name('admin.promotions.index');
        Route::post('/', [PromotionController::class, 'store'])->name('admin.promotions.store');
        Route::put('/{id}', [PromotionController::class, 'update'])->name('admin.promotions.update');
        Route::delete('/{id}', [PromotionController::class, 'delete'])->name('admin.promotions.delete');
        Route::put('/active/{id}', [PromotionController::class, 'activePromotion'])->name('admin.promotions.activate');
    });

    //Aply Promotions
    Route::prefix('promotions/aply')->group(function () {
        Route::get('/', [AplyPromController::class, 'index'])->name('admin.aplyProms.view');
        Route::post('/store', [AplyPromController::class, 'aplyPromotion'])->name('admin.aplyProms.store');
        Route::put('/update', [AplyPromController::class, 'update'])->name('admin.aplyProms.update');
        Route::delete('/delete', [AplyPromController::class, 'delete'])->name('admin.aplyProms.delete');
    });
    

    // User Routes
    Route::prefix('users')->group(function () {
        Route::get('/', [UserAdminController::class, 'index'])->name('admin.users.index');
        Route::get('/{userId}', [UserAdminController::class, 'show'])->name('admin.users.show');
    });
});

// End Admin Routes
require __DIR__ . '/auth.php';
