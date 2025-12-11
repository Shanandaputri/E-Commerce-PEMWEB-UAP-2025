<?php

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Seller\CategoryController as SellerCategoryController;
use App\Http\Controllers\Seller\ProductController as SellerProductController;
use App\Http\Controllers\Seller\OrderController as SellerOrderController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HistoryController;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Http\Controllers\Admin\StoreVerificationController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\AdminDashboardController;

// =====================
// PUBLIC ROUTES
// =====================

// HOMEPAGE
Route::get('/', function () {
    Auth::logout();
    $categories = ProductCategory::all();

    $allProducts = Product::with(['productImages' => function ($q) {
        $q->where('is_thumbnail', 1);
    }])->get();

    $newArrivals = $allProducts->take(4);
    $topSelling  = $allProducts->skip(4)->take(4);

    return view('home', [
        'categories'  => $categories,
        'allProducts' => $allProducts,
        'newArrivals' => $newArrivals,
        'topSelling'  => $topSelling,
    ]);
})->name('home');

// LIST KATEGORI
Route::get('/categories', [CategoryController::class, 'index'])
    ->name('categories.index');

// DETAIL PRODUCT  (PUBLIC)
Route::get('/product/{slug}', [ProductController::class, 'show'])
    ->name('product.show');

// DETAIL KATEGORI (PUBLIC)
Route::get('/category/{id}', [CategoryController::class, 'show'])
    ->name('category.show');

// LIVE SEARCH PRODUCT (PUBLIC)
Route::get('/search/products', [ProductController::class, 'search'])
    ->name('products.search');

    // routes/web.php

Route::get('/products/search', [ProductController::class, 'search'])
    ->name('products.search');

// =====================
// AUTH ROUTES
// =====================

// AUTO-REDIRECT DASHBOARD
Route::get('/dashboard', function () {
    $user = auth()->user();

    if ($user->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }

    if ($user->isSeller()) {
        return redirect()->route('seller.dashboard');
    }

    return redirect()->route('customer.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    // PROFILE
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ADMIN
    Route::middleware('role:admin')
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {
            Route::get('/dashboard', function () {
                return view('admin.dashboard');
            })->name('dashboard');
        });

    // CUSTOMER (MEMBER)
    Route::middleware('role:member')
        ->prefix('customer')
        ->name('customer.')
        ->group(function () {
            Route::get('/dashboard', function () {
                $categories = ProductCategory::all();

                $allProducts = Product::with(['productImages' => function ($q) {
                    $q->where('is_thumbnail', 1);
                }])->get();

                $newArrivals = $allProducts->take(4);
                $topSelling  = $allProducts->skip(4)->take(4);

                return view('customer.dashboard', [
                    'categories'  => $categories,
                    'allProducts' => $allProducts,
                    'newArrivals' => $newArrivals,
                    'topSelling'  => $topSelling,
                ]);
            })->name('dashboard');
        });

    // TRANSAKSI LIST
    Route::get('/transactions', [TransactionController::class, 'index'])
        ->name('transactions.index');

    // STORE REGISTER (MEMBER)
    Route::middleware('role:member')->group(function () {
        Route::get('/store/register', [StoreController::class, 'create'])->name('store.register');
        Route::post('/store/register', [StoreController::class, 'store'])->name('store.store');
    });

    // WALLET
    Route::get('/wallet', [WalletController::class, 'index'])->name('wallet.index');
    Route::get('/wallet/topup', [WalletController::class, 'topup'])->name('wallet.topup');
    Route::post('/wallet/topup', [WalletController::class, 'storeTopup'])->name('wallet.topup.store');

    // PAYMENT PAGE
    Route::get('/payment', [WalletController::class, 'paymentForm'])->name('payment.form');
    Route::post('/payment/confirm', [WalletController::class, 'confirmPayment'])->name('payment.confirm');

    // CART
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
    Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/{cart}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{cart}', [CartController::class, 'destroy'])->name('cart.destroy');

    // CHECKOUT
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');

    // HISTORY
    Route::get('/history', [HistoryController::class, 'index'])->name('customer.history');

      // SELLER (YANG PUNYA STORE)
    Route::middleware('seller')
        ->prefix('seller')
        ->name('seller.')
        ->group(function () {
            Route::get('/dashboard', function () {
                return view('seller.dashboard');
            })->name('dashboard');

            // KATEGORI
            Route::get('/categories', [SellerCategoryController::class, 'index'])->name('categories.index');
            Route::get('/categories/create', [SellerCategoryController::class, 'create'])->name('categories.create');
            Route::post('/categories', [SellerCategoryController::class, 'store'])->name('categories.store');
            Route::get('/categories/{category}/edit', [SellerCategoryController::class, 'edit'])->name('categories.edit');
            Route::put('/categories/{category}', [SellerCategoryController::class, 'update'])->name('categories.update');
            Route::delete('/categories/{category}', [SellerCategoryController::class, 'destroy'])->name('categories.destroy');

            // PRODUK
            Route::get('/products', [SellerProductController::class, 'index'])->name('products.index');
            Route::get('/products/create', [SellerProductController::class, 'create'])->name('products.create');
            Route::post('/products', [SellerProductController::class, 'store'])->name('products.store');
            Route::get('/products/{product}/edit', [SellerProductController::class, 'edit'])->name('products.edit');
            Route::put('/products/{product}', [SellerProductController::class, 'update'])->name('products.update');
            Route::delete('/products/{product}', [SellerProductController::class, 'destroy'])->name('products.destroy');

            // Orders
            Route::get('/orders', [SellerOrderController::class, 'index'])->name('orders.index');
            Route::get('/orders/{transaction}', [SellerOrderController::class, 'show'])->name('orders.show');
            Route::patch('/orders/{transaction}/status', [SellerOrderController::class, 'updateStatus'])->name('orders.updateStatus');
    
        });
    
    // ADMIN
Route::middleware('role:admin')
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('dashboard');

        // VERIFIKASI TOKO
        Route::get('/verification', [StoreVerificationController::class, 'index'])
            ->name('verification.index');
        Route::post('/verification/{store}/approve', [StoreVerificationController::class, 'approve'])
            ->name('verification.approve');
        Route::post('/verification/{store}/reject', [StoreVerificationController::class, 'reject'])
            ->name('verification.reject');

        // MANAJEMEN USER & STORE
        Route::get('/users', [UserManagementController::class, 'index'])
            ->name('users.index');
        Route::patch('/users/{user}/role', [UserManagementController::class, 'updateRole'])
            ->name('users.updateRole');
        Route::delete('/users/{user}', [UserManagementController::class, 'destroy'])
            ->name('users.destroy');
    });
});

require __DIR__.'/auth.php';
