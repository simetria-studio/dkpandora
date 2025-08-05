<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;

Route::get('/', function () {
    return redirect()->route('products.index');
});

// Rotas de produtos
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

// Rotas do carrinho
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/{product}', [CartController::class, 'add'])->name('cart.add');
Route::delete('/cart/{product}', [CartController::class, 'remove'])->name('cart.remove');
Route::put('/cart/{product}', [CartController::class, 'update'])->name('cart.update');

// Rotas de pedidos (requerem autenticação)
Route::middleware('auth')->group(function () {
    Route::get('/checkout', [OrderController::class, 'checkout'])->name('orders.checkout');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');

    // Rotas de pagamento
    Route::middleware('stripe')->group(function () {
        Route::get('/payments/{order}/process', [PaymentController::class, 'processPayment'])->name('payments.process');
        Route::post('/payments/{order}/confirm', [PaymentController::class, 'confirmPayment'])->name('payments.confirm');
        
        // Rotas PIX
        Route::get('/payments/{order}/pix', [PaymentController::class, 'processPixPayment'])->name('payments.pix');
        Route::post('/payments/{order}/pix/status', [PaymentController::class, 'checkPixStatus'])->name('payments.pix.status');
    });
});

// Webhook do Stripe (não requer autenticação)
Route::post('/webhook/stripe', [PaymentController::class, 'webhook'])->name('webhook.stripe');

require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
