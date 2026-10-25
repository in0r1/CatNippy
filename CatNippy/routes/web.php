<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AvatarController;
use App\Http\Controllers\AdminOrderController;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\NetworkDiagnosticsController;
use App\Http\Controllers\SupplierStockController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::get('/', [ProductController::class, 'index'])->name('home');

Route::get('/products', [ProductController::class, 'index'])
    ->name('products.index');

Route::get('/products/{product:slug}', [ProductController::class, 'show'])
    ->name('products.show');

/*
|--------------------------------------------------------------------------
| Shopping cart
|--------------------------------------------------------------------------
*/

Route::get('/cart', [CartController::class, 'index'])
    ->name('cart.index');

Route::post('/cart/{product:slug}', [CartController::class, 'store'])
    ->name('cart.store');

Route::patch('/cart/{product:slug}', [CartController::class, 'update'])
    ->name('cart.update');

Route::delete('/cart/{product:slug}', [CartController::class, 'destroy'])
    ->name('cart.destroy');

Route::get('/checkout', [CheckoutController::class, 'index'])
    ->name('checkout.index');

Route::post('/checkout', [CheckoutController::class, 'store'])
    ->name('checkout.store');

Route::get('/checkout/confirmation', [CheckoutController::class, 'confirmation'])
    ->name('checkout.confirmation');

Route::middleware('auth')->prefix('account')->group(function () {
    Route::get('/orders', [OrderController::class, 'index'])
        ->name('orders.index');

    Route::get('/orders/{order}', [OrderController::class, 'show'])
        ->name('orders.show');
});

Route::post('/profile/avatar', [AvatarController::class, 'update'])
    ->middleware('auth')
    ->name('profile.avatar.update');

Route::get('/admin/orders', [AdminOrderController::class, 'index'])
    ->middleware('auth')
    ->name('admin.orders.index');

Route::post(
    '/products/{product:slug}/reviews',
    [ReviewController::class, 'store']
)
    ->middleware('auth')
    ->name('products.reviews.store');

Route::get('/orders/{order}/invoice', [InvoiceController::class, 'download'])
    ->name('orders.invoice');

Route::middleware('auth')->prefix('admin')->group(function () {
    Route::get('/tools/connectivity', [
        NetworkDiagnosticsController::class,
        'index',
    ])->name('tools.connectivity');

    Route::post('/tools/connectivity', [
        NetworkDiagnosticsController::class,
        'check',
    ])->name('tools.connectivity.check');
});

Route::middleware('auth')->group(function () {
    Route::get('/admin/catalog/stock', [
        SupplierStockController::class,
        'index',
    ])->name('admin.catalog.stock');

    Route::post('/admin/catalog/stock/refresh', [
        SupplierStockController::class,
        'refresh',
    ])->name('admin.catalog.stock.refresh');
});