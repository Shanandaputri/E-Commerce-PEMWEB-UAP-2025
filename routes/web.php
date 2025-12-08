<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Seller\CategoryController as SellerCategoryController;
use App\Http\Controllers\Seller\ProductController as SellerProductController;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\CartController;



// HOMEPAGE
Route::get('/', function () {
    $categories = ProductCategory::all();

    // Ambil semua produk + thumbnail
    $allProducts = Product::with(['productImages' => function ($q) {
        $q->where('is_thumbnail', 1);
    }])->get();

    // Bagi jadi section-section
    $newArrivals = $allProducts->take(4);
    $topSelling  = $allProducts->skip(4)->take(4);

    return view('home', [
        'categories'  => $categories,
        'allProducts' => $allProducts,
        'newArrivals' => $newArrivals,
        'topSelling'  => $topSelling,
    ]);
})->name('home');

// HALAMAN DETAIL PRODUK
Route::get('/product/{slug}', [ProductController::class, 'show'])
    ->name('product.show');

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

    // ADMIN ONLY
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

                // === copy logic dari route '/' ===
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

        Route::get('/transactions', [TransactionController::class, 'index'])
    ->name('transactions.index');



    // STORE REGISTER (MEMBER)
    Route::middleware('role:member')->group(function () {

        // form daftar toko
        Route::get('/store/register', [StoreController::class, 'create'])
            ->name('store.register');

        // submit form daftar toko
        Route::post('/store/register', [StoreController::class, 'store'])
            ->name('store.store');
    });

    // SELLER ROUTE (MEMBER YG PUNYA STORE & VERIFIED)
    Route::middleware('seller')
        ->prefix('seller')
        ->name('seller.')
        ->group(function () {
            // DASHBOARD SELLER
            Route::get('/dashboard', function () {
                return view('seller.dashboard');
            })->name('dashboard');

            // ================== KATEGORI (CRUD) ==================
            Route::get('/categories', [SellerCategoryController::class, 'index'])
                ->name('categories.index');

            Route::get('/categories/create', [SellerCategoryController::class, 'create'])
                ->name('categories.create');

            Route::post('/categories', [SellerCategoryController::class, 'store'])
                ->name('categories.store');

            Route::get('/categories/{category}/edit', [SellerCategoryController::class, 'edit'])
                ->name('categories.edit');

            Route::put('/categories/{category}', [SellerCategoryController::class, 'update'])
                ->name('categories.update');

            Route::delete('/categories/{category}', [SellerCategoryController::class, 'destroy'])
                ->name('categories.destroy');

            // ================== PRODUK (CRUD) ==================
            Route::get('/products', [SellerProductController::class, 'index'])
                ->name('products.index');

            Route::get('/products/create', [SellerProductController::class, 'create'])
                ->name('products.create');

            Route::post('/products', [SellerProductController::class, 'store'])
                ->name('products.store');

            Route::get('/products/{product}/edit', [SellerProductController::class, 'edit'])
                ->name('products.edit');

            Route::put('/products/{product}', [SellerProductController::class, 'update'])
                ->name('products.update');

            Route::delete('/products/{product}', [SellerProductController::class, 'destroy'])
                ->name('products.destroy');
        });

    // WALLET ROUTES
    Route::get('/wallet', [WalletController::class, 'index'])->name('wallet.index');
    Route::get('/wallet/topup', [WalletController::class, 'topup'])->name('wallet.topup');
    Route::post('/wallet/topup', [WalletController::class, 'storeTopup'])->name('wallet.topup.store');

    // Payment page
    Route::get('/payment', [WalletController::class, 'paymentForm'])->name('payment.form');
    Route::post('/payment/confirm', [WalletController::class, 'confirmPayment'])->name('payment.confirm');
});

Route::middleware('auth')->group(function () {
    Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index'); // nanti buat halaman keranjang
});

require __DIR__.'/auth.php';
