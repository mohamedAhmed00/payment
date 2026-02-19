<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PaymentSettingsController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::middleware('auth:sanctum')->group(function (){
    Route::post('pay', [PaymentController::class, 'pay'])->name('pay');
    Route::post('refund', [PaymentController::class, 'refund'])->name('refund');
    Route::get('user/payment/settings', [PaymentSettingsController::class, 'userPaymentSettings'])->name('user.payment.settings');
    Route::get('user/transaction/{client_key}', [PaymentSettingsController::class, 'userTransactions'])->name('user.transaction');
});
