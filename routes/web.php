<?php

use App\Http\Controllers\Admin\AdminActionController;
use App\Http\Controllers\Admin\AdminPasswordResetController;
use App\Http\Controllers\Admin\AdminViewController;
use App\Http\Controllers\Admin\CashierController;
use App\Http\Controllers\Admin\CategoryFoodController;
use App\Http\Controllers\Admin\FoodController;
use App\Http\Controllers\Admin\TableController;
use App\Http\Controllers\Admin\TableServiceController;
use App\Http\Controllers\CashierAuthController;
use App\Http\Controllers\CashierDashboardController;
use App\Http\Controllers\MenuController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/menu/bill/{number}', [MenuController::class, 'index'])->middleware('table.session')->name('menu.index');
Route::get('/menu/bill/data/{number}', [MenuController::class, 'updateData'])->middleware('table.session')->name('menu.index.data');
Route::post('/bill/new/orders', [MenuController::class, 'newOrders'])
    ->name('bill.new.orders');
Route::get('menu/{code}', [MenuController::class, 'createToken'])->name('menu.login');

Route::get('/cashier/login', fn () => redirect()->route('admin.login', ['type' => 'cashier']))
    ->name('cashier.login');
Route::post('/cashier/login', [CashierAuthController::class, 'login'])->name('cashier.login.action');
Route::middleware('cashier.auth')->prefix('cashier')->name('cashier.')->group(function () {
    Route::get('/tables', [CashierDashboardController::class, 'index'])->name('tables');
    Route::post('/orders/{order}/confirm', [CashierDashboardController::class, 'confirmOrder'])
        ->name('orders.confirm');
    Route::post('/logout', [CashierAuthController::class, 'logout'])->name('logout');
});

Route::prefix('admin')->group(function () {

    // /////////////////////////////  Auth  ///////////////////////////////

    Route::get('login', [AdminViewController::class, 'login'])->name('admin.login');
    Route::post('login', [AdminActionController::class, 'login'])->name('admin.login.action');

    // /////////////////////////////  START AUTH  ///////////////////////////////

    Route::group(['middleware' => 'admin.auth'], function () {
        Route::post('logout', [AdminActionController::class, 'logout'])->name('admin.logout');

        Route::get('dashboard', [AdminViewController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('cashiers', [CashierController::class, 'index'])->name('admin.cashiers.index');
        Route::post('cashiers', [CashierController::class, 'store'])->name('admin.cashiers.store');
        Route::get('cashiers/{cashier}/edit', [CashierController::class, 'edit'])->name('admin.cashiers.edit');
        Route::put('cashiers/{cashier}', [CashierController::class, 'update'])->name('admin.cashiers.update');
        Route::patch('cashiers/{cashier}/toggle-status', [CashierController::class, 'toggleStatus'])
            ->name('admin.cashiers.toggle-status');

        // /////////////////////////////  END AUTH  ///////////////////////////////

        // /////////////////////////////  START SERVICES  ///////////////////////////////

        Route::get('/table-services', [TableServiceController::class, 'index'])->name('admin.settings.index');
        Route::post('/table-services', [TableServiceController::class, 'store'])->name('admin.settings.store');
        Route::put('/table-services/{serviceId}', [TableServiceController::class, 'update'])->name('admin.settings.update');
        Route::delete('/table-services/{serviceId}', [TableServiceController::class, 'destroy'])->name('admin.settings.destroy');
        Route::patch('/table-services/{serviceId}/toggle', [TableServiceController::class, 'toggleStatus'])->name('admin.settings.toggle-status');

        // /////////////////////////////  END SERVICES  ///////////////////////////////

        // /////////////////////////////  Table  ///////////////////////////////

        Route::prefix('tables')->group(function () {
            Route::get('/', [TableController::class, 'index'])->name('tables.index');
            Route::get('/create', [TableController::class, 'create'])->name('tables.create');
            Route::post('/create', [TableController::class, 'store'])->name('tables.store');
            Route::get('/{table}/edit', [TableController::class, 'edit'])->name('tables.edit');
            Route::put('/{table}/edit', [TableController::class, 'update'])->name('tables.update');
            Route::delete('/{table}/delete', [TableController::class, 'destroy'])->name('tables.destroy');
            Route::get('/{table}/qr', [TableController::class, 'qr'])->name('tables.qr');
        });
        // ///////////////////////////  Table  ///////////////////////////////

        // ///////////////////////////  Category Food  ///////////////////////////////

        Route::resource('category-food', CategoryFoodController::class)->names('admin.category-food');
        Route::patch('category-food/{categoryFood}/toggle-status', [CategoryFoodController::class, 'toggleStatus'])->name('admin.category-food.toggle-status');

        // ///////////////////////////  Category Food  ///////////////////////////////

        // ///////////////////////////  Food  ///////////////////////////////

        // مسارات الوجبات التابعة لتصنيف معين
        Route::prefix('category-food/{category}')->name('admin.category-food.food.')->group(function () {
            Route::get('food', [FoodController::class, 'index'])->name('index');
            Route::get('food/create', [FoodController::class, 'create'])->name('create');
            Route::post('food', [FoodController::class, 'store'])->name('store');
            Route::get('food/{food}', [FoodController::class, 'show'])->name('show');
            Route::get('food/{food}/edit', [FoodController::class, 'edit'])->name('edit');
            Route::put('food/{food}', [FoodController::class, 'update'])->name('update');
            Route::delete('food/{food}', [FoodController::class, 'destroy'])->name('destroy');
            Route::patch('food/{food}/toggle-status', [FoodController::class, 'toggleStatus'])->name('toggle-status');
        });

        // ///////////////////////////  Food  ///////////////////////////////

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
