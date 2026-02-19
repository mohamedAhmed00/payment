<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\UserManagementController;
use Illuminate\Support\Facades\Route;

require __DIR__.'/auth.php';


Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard.dashboard');
    })->name('dashboard');
    Route::resource('organization', OrganizationController::class);
    Route::get('organization/transactions/{organization}', [OrganizationController::class, 'listOrganizationTransactions'])->name('organization.transaction');
    Route::resource('group', GroupController::class);
    Route::get('user/group/{group}/permission', [PermissionController::class, 'index'])->name('permission.index');
    Route::get('permission/fresh', [PermissionController::class, 'store'])->name('permission.seed');
    Route::patch('user/group/{group}/permission', [PermissionController::class, 'update'])->name('permission.update');
    Route::get('activity-log', [ActivityLogController::class, 'index'])->name('activity.logs.index');
    Route::resource('user', UserManagementController::class)->except(['show']);
    Route::get('user/transactions/{user}', [UserManagementController::class, 'listUserTransactions'])->name('user.transactions');
    Route::get('user/payment_settings/{user}', [UserManagementController::class, 'paymentSettings'])->name('user.payment_settings');
    Route::put('user/payment_settings/{user}', [UserManagementController::class, 'savePaymentSettings'])->name('user.payment_settings.update');
});

Route::get('render_iframe', [PaymentController::class, 'renderIframe'])->name('render_iframe');
Route::match(['get', 'post'], 'returning', [PaymentController::class, 'returning'])->name('returning');
Route::match(['get', 'post'], 'callback', [PaymentController::class, 'callback'])->name('callback');


