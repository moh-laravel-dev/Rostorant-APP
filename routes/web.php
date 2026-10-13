<?php

use App\Http\Controllers\Admin\AdminActionController;
use App\Http\Controllers\Admin\AdminPasswordResetController;
use App\Http\Controllers\Admin\AdminViewController;
use App\Http\Controllers\Admin\TableController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::prefix('admin')->group(function () {
    Route::get('login', [AdminViewController::class, 'login'])->name('admin.login');
    Route::post('login', [AdminActionController::class, 'login'])->name('admin.login.action');
    Route::group(['middleware' => 'admin.auth'], function () {
        Route::get('dashboard', [AdminViewController::class, 'dashboard'])->name('admin.dashboard');
        Route::post('logout', [AdminActionController::class, 'logout'])->name('admin.logout');
        Route::prefix('tables')->group(function () {
            Route::get('/', [TableController::class, 'index'])->name('tables.index');

            Route::get('/{table}/qr', [TableController::class, 'qr'])->name('tables.qr');
            Route::get('/{table}/qr/print', [TableController::class, 'qrPrint'])->name('tables.qr.print');

        });
    });
});



Route::get('/forgot-password', [AdminPasswordResetController::class, 'create'])
    ->name('password.request');
Route::post('/forgot-password', [AdminPasswordResetController::class, 'sendResetLink'])
    ->name('password.email');
Route::get('/reset-password/{token}', [AdminPasswordResetController::class, 'edit'])
    ->name('password.reset');
Route::post('/reset-password', [AdminPasswordResetController::class, 'reset'])
    ->name('password.update');