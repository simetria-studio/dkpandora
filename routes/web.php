<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PayPalController;
use App\Http\Controllers\RewardController;

Route::get('/', [HomeController::class, 'index'])->name('home');

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
    Route::get('/orders/{order}/payment', [OrderController::class, 'paymentSelection'])->name('orders.payment');

    // Rotas de pagamento
    Route::middleware('stripe')->group(function () {
        Route::get('/payments/{order}/process', [PaymentController::class, 'processPayment'])->name('payments.process');
        Route::post('/payments/{order}/confirm', [PaymentController::class, 'confirmPayment'])->name('payments.confirm');

        // Rotas PIX
        Route::get('/payments/{order}/pix', [PaymentController::class, 'processPixPayment'])->name('payments.pix');
        Route::post('/payments/{order}/pix/status', [PaymentController::class, 'checkPixStatus'])->name('payments.pix.status');
    });

    // Rotas PayPal
    Route::get('/paypal/{order}/process', [PayPalController::class, 'processPayment'])->name('paypal.process');
    Route::get('/paypal/{order}/success', [PayPalController::class, 'success'])->name('paypal.success');
    Route::get('/paypal/{order}/cancel', [PayPalController::class, 'cancel'])->name('paypal.cancel');

    // Rotas de recompensas
    Route::get('/rewards', [RewardController::class, 'index'])->name('rewards.index');
    Route::post('/rewards/{reward}/redeem', [RewardController::class, 'redeem'])->name('rewards.redeem');
    Route::get('/rewards/check-eligible', [RewardController::class, 'checkEligibleRewards'])->name('rewards.check-eligible');
});

// Webhook do Stripe (não requer autenticação)
Route::post('/webhook/stripe', [PaymentController::class, 'webhook'])->name('webhook.stripe');

// Webhook do PayPal (não requer autenticação)
Route::post('/webhook/paypal', [PayPalController::class, 'webhook'])->name('webhook.paypal');

require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
