<?php

use App\Http\Controllers\Admin\AdminActionController;
use App\Http\Controllers\Admin\AdminPasswordResetController;
use App\Http\Controllers\Admin\AdminViewController;
use App\Http\Controllers\Admin\CategoryFoodController;
use App\Http\Controllers\Admin\TableController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\TableServiceController;

Route::get('/', function () {
    return view('welcome');
});
Route::prefix('admin')->group(function () {

    ///////////////////////////////  Auth  ///////////////////////////////

    Route::get('login', [AdminViewController::class, 'login'])->name('admin.login');
    Route::post('login', [AdminActionController::class, 'login'])->name('admin.login.action');

    ///////////////////////////////  START AUTH  ///////////////////////////////

    Route::group(['middleware' => 'admin.auth'], function () {
        Route::post('logout', [AdminActionController::class, 'logout'])->name('admin.logout');

        Route::get('dashboard', [AdminViewController::class, 'dashboard'])->name('admin.dashboard');

        ///////////////////////////////  END AUTH  ///////////////////////////////

        ///////////////////////////////  START SERVICES  ///////////////////////////////

        Route::get('/table-services', [TableServiceController::class, 'index'])->name('admin.settings.index');
        Route::post('/table-services', [TableServiceController::class, 'store'])->name('admin.settings.store');
        Route::put('/table-services/{serviceId}', [TableServiceController::class, 'update'])->name('admin.settings.update');
        Route::delete('/table-services/{serviceId}', [TableServiceController::class, 'destroy'])->name('admin.settings.destroy');
        Route::patch('/table-services/{serviceId}/toggle', [TableServiceController::class, 'toggleStatus'])->name('admin.settings.toggle-status');

        ///////////////////////////////  END SERVICES  ///////////////////////////////

        ///////////////////////////////  Table  ///////////////////////////////

        Route::prefix('tables')->group(function () {
            Route::get('/', [TableController::class, 'index'])->name('tables.index');
            Route::get('/create', [TableController::class, 'create'])->name('tables.create');
            Route::post('/create', [TableController::class, 'store'])->name('tables.store');
            Route::get('/{table}/edit', [TableController::class, 'edit'])->name('tables.edit');
            Route::put('/{table}/edit', [TableController::class, 'update'])->name('tables.update');
            Route::delete('/{table}/delete', [TableController::class, 'destroy'])->name('tables.destroy');
            Route::get('/{table}/qr', [TableController::class, 'qr'])->name('tables.qr');
        });
        /////////////////////////////  Table  ///////////////////////////////

        /////////////////////////////  Category Food  ///////////////////////////////

        Route::resource('category-food', CategoryFoodController::class)->names('admin.category-food');
        Route::patch('category-food/{categoryFood}/toggle-status', [CategoryFoodController::class, 'toggleStatus',])->name('admin.category-food.toggle-status');

        /////////////////////////////  Category Food  ///////////////////////////////
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