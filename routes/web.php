<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StoreController;
use App\Models\Product;


Route::get('/', function () {
    return view('welcome');
})->name('home');

// AUTO-REDIRECT DASHBOARD
Route::get('/dashboard', function () {
    $user = auth()->user();

    return match ($user->role) {
        'admin'  => redirect()->route('admin.dashboard'),
        'member' => redirect()->route('customer.dashboard'),
        default  => redirect()->route('home'),
    };
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
                return view('customer.dashboard');
            })->name('dashboard');
        });

    // STORE REGISTER (MEMBER)
    Route::middleware('role:member')->group(function () {

        // form daftar toko
        Route::get('/store/register', [StoreController::class, 'create'])
            ->name('store.register');

        // submit form daftar toko
        Route::post('/store/register', [StoreController::class, 'store'])
            ->name('store.store');
    });

    // SELLER ROUTE (MEMBER YG PUNYA STORE)
    Route::middleware('seller')
        ->prefix('seller')
        ->name('seller.')
        ->group(function () {
            Route::get('/dashboard', function () {
                return "Seller Dashboard (member + punya store verified)";
            })->name('dashboard');
        });
});

Route::get('/test-products', function () {
    $products = Product::with(['productImages' => function ($q) {
        $q->where('is_thumbnail', 1);
    }])->get();

    return view('test-products', compact('products'));
});

require __DIR__.'/auth.php';
