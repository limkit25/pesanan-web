<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/dashboard', function () {
    if (in_array(auth()->user()->role, ['admin', 'dapur'])) {
        return redirect()->route('admin.dashboard');
    }
    // Pembeli langsung kembali ke beranda
    return redirect()->route('home');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        $totalOrders = \App\Models\Order::count();
        $totalProducts = \App\Models\Product::count();
        $totalCategories = \App\Models\Category::count();
        $pendingOrders = \App\Models\Order::where('status', 'pending')->count();
        
        // H-3 Upcoming Deliveries
        $upcomingDeliveriesCount = \App\Models\Order::whereIn('status', ['pending', 'processing'])
            ->whereNotNull('delivery_date')
            ->whereBetween('delivery_date', [
                \Carbon\Carbon::now()->startOfDay(), 
                \Carbon\Carbon::now()->addDays(3)->endOfDay()
            ])
            ->count();
            
        return view('admin.dashboard', compact('totalOrders', 'totalProducts', 'totalCategories', 'pendingOrders', 'upcomingDeliveriesCount'));
    })->name('dashboard');

    Route::get('/orders/check-new', [\App\Http\Controllers\Admin\OrderController::class, 'checkNewOrders'])->middleware('throttle:30,1')->name('orders.check');
    Route::get('/orders/{order}/invoice', [\App\Http\Controllers\Admin\OrderController::class, 'invoice'])->name('orders.invoice');
    Route::resource('orders', \App\Http\Controllers\Admin\OrderController::class)->only(['index', 'show', 'edit', 'update']);
    Route::get('/reports/delivery', [\App\Http\Controllers\Admin\ReportController::class, 'deliveryRecap'])->name('reports.delivery');
    Route::get('/reports', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export', [\App\Http\Controllers\Admin\ReportController::class, 'export'])->name('reports.export');

    Route::middleware('superadmin')->group(function () {
        Route::resource('categories', CategoryController::class);
        Route::resource('products', ProductController::class);
        Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
        Route::get('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
        Route::post('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Cart Routes
    Route::get('/cart', [\App\Http\Controllers\CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{product}', [\App\Http\Controllers\CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/update/{cart}', [\App\Http\Controllers\CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{cart}', [\App\Http\Controllers\CartController::class, 'remove'])->name('cart.remove');

    // Checkout Routes
    Route::get('/checkout', [\App\Http\Controllers\CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [\App\Http\Controllers\CheckoutController::class, 'store'])->name('checkout.store');

    // Customer Order History
    Route::get('/orders', [\App\Http\Controllers\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [\App\Http\Controllers\OrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{order}/invoice', [\App\Http\Controllers\OrderController::class, 'invoice'])->name('orders.invoice');
    Route::post('/orders/{order}/payment-proof', [\App\Http\Controllers\OrderController::class, 'uploadPaymentProof'])->name('orders.upload_payment');
    Route::post('/orders/{order}/cancel', [\App\Http\Controllers\OrderController::class, 'cancel'])->name('orders.cancel');
    Route::post('/orders/{order}/review/{product}', [\App\Http\Controllers\ReviewController::class, 'store'])->name('reviews.store');
});

Route::get('auth/google', [\App\Http\Controllers\Auth\GoogleController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [\App\Http\Controllers\Auth\GoogleController::class, 'handleGoogleCallback']);

require __DIR__.'/auth.php';
