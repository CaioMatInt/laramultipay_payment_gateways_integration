<?php

use App\Http\Controllers\PaymentController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('users')->group(function () {
    Route::post('login', [UserController::class, 'login'])->name('user.login');
    Route::get('login/{provider_name}', [
        UserController::class, 'redirectToLoginWithProvider'
    ])->name('user.login.provider');

    Route::get('login/{provider_name}/callback', [
        UserController::class, 'loginCallbackOfProvider'
    ])->name('user.login.provider.callback');

    Route::post('register', [UserController::class, 'register'])->name('user.register');
    Route::post('password/forgot', [UserController::class, 'sendPasswordResetLinkEmail'])->name('user.password.forgot');
    Route::post('password/reset', [UserController::class, 'resetPassword'])->name('user.password.reset');

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [UserController::class, 'logout'])->name('user.logout');
        Route::get('me', [UserController::class, 'getAuthenticatedUser'])->name('user.me');
        Route::post('{id}/update', [UserController::class, 'update'])->name('user.update');
    });
});

Route::prefix('payments')->middleware('auth:sanctum')->group(function () {
    Route::post('', [PaymentController::class, 'create'])->name('payment.create');
});
