<?php

use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\PaymentGatewayKeyController;
use App\Http\Controllers\TransactionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('users')->group(function () {
    Route::post('login', [AuthenticationController::class, 'login'])->name('user.login');
    Route::get('login/{provider_name}', [
        AuthenticationController::class, 'redirectToLoginWithProvider'
    ])->name('user.login.provider');

    Route::get('login/{provider_name}/callback', [
        AuthenticationController::class, 'loginCallbackOfProvider'
    ])->name('user.login.provider.callback');

    Route::post('register', [AuthenticationController::class, 'register'])->name('user.register');
    Route::post('password/forgot', [AuthenticationController::class, 'sendPasswordResetLinkEmail'])->name('user.password.forgot');
    Route::post('password/reset', [AuthenticationController::class, 'resetPassword'])->name('user.password.reset');

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [AuthenticationController::class, 'logout'])->name('user.logout');
        Route::get('me', [AuthenticationController::class, 'getAuthenticatedUser'])->name('user.me');
        Route::post('{id}/update', [AuthenticationController::class, 'update'])->name('user.update');
    });
});

Route::prefix('payments')->middleware('auth:sanctum')->group(function () {
    Route::post('', [PaymentController::class, 'store'])->name('payment.store');
    Route::get('', [PaymentController::class, 'index'])->name('payment.index');
    Route::get('/{uuid}', [PaymentController::class, 'show'])->name('payment.show');
});

Route::get('payments/{uuid}/redirect', [PaymentController::class, 'redirectToGatewayPaymentPage'])
    ->name('payment.redirect-to-gateway-payment-page');

Route::prefix('payment-gateway-keys')->middleware('auth:sanctum')->group(function () {
    Route::post('', [PaymentGatewayKeyController::class, 'store'])->name('payment-gateway-key.store');
});
