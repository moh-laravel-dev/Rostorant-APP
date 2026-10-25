<?php

use App\Http\Controllers\Admin\AdminActionController;
use App\Http\Controllers\Admin\AdminPasswordResetController;
use App\Http\Controllers\Admin\AdminViewController;
use App\Http\Controllers\Admin\BillsController;
use App\Http\Controllers\Admin\CashierController;
use App\Http\Controllers\Admin\CaptainController;
use App\Http\Controllers\Admin\CategoryFoodController;
use App\Http\Controllers\Admin\FoodController;
use App\Http\Controllers\Admin\TableController;
use App\Http\Controllers\Admin\TableServiceController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\CashierAuthController;
use App\Http\Controllers\CashierDashboardController;
use App\Http\Controllers\CaptainAuthController;
use App\Http\Controllers\CaptainDashboardController;
use App\Http\Controllers\MenuController;
use Illuminate\Support\Facades\Route;

Route::middleware(['verify.csrf.token', 'global.rate.limit'])->group(function () {

    Route::get('/', function () {
        return view('welcome');
    })->name('welcome');

    Route::get('/menu/bill/{number}', [MenuController::class, 'index'])->middleware('table.session')->name('menu.index');
    Route::get('/menu/bill/data/{number}', [MenuController::class, 'updateData'])->middleware('table.session')->name('menu.index.data');
    Route::post('/bill/new/orders', [MenuController::class, 'newOrders'])
        ->name('bill.new.orders');
    Route::get('menu-page', [MenuController::class, 'menuPage'])->name('menu.page');
    Route::get('menu-page/{code}/{from?}', [MenuController::class, 'createToken'])->name('menu.page_login');
    Route::get('menu/{code}', [MenuController::class, 'createToken'])->name('menu.login');
    Route::get('jone-to-bill', [BillController::class, 'joneToBill'])->name('jone.to.bill');
    Route::post('bill-finalize', [BillController::class, 'billFinalize'])->middleware('bill.finalize.rate.limit')->name('bill.finalize');
    Route::patch('bill-finalize', [BillController::class, 'confirmBillFinalize'])->name('confirm.bill.finalize');
    //
    Route::get('/cashier/login', fn () => redirect()->route('admin.login', ['type' => 'cashier']))
        ->name('cashier.login');
    Route::post('/cashier/login', [CashierAuthController::class, 'login'])->name('cashier.login.action');
    Route::middleware('cashier.auth')->prefix('cashier')->name('cashier.')->group(function () {
        Route::get('/tables', [CashierDashboardController::class, 'index'])->name('tables');
        Route::post('/orders/{order}/confirm', [CashierDashboardController::class, 'confirmOrder'])
            ->name('orders.confirm');
        Route::post('/logout', [CashierAuthController::class, 'logout'])->name('logout');
    });
    Route::get('/captain/login', fn () => redirect()->route('admin.login', ['type' => 'captain']))
        ->name('captain.login');
    Route::post('/captain/login', [CaptainAuthController::class, 'login'])->name('captain.login.action');
    Route::middleware('captain.auth')->prefix('captain')->name('captain.')->group(function () {
        Route::get('/tables', [CaptainDashboardController::class, 'index'])->name('tables');
        Route::post('/logout', [CaptainAuthController::class, 'logout'])->name('logout');
    });

    Route::prefix('admin')->group(function () {

        // /////////////////////////////  Auth  ///////////////////////////////

        Route::get('login', [AdminViewController::class, 'login'])->name('admin.login');
        Route::post('login', [AdminActionController::class, 'login'])->name('admin.login.action');

        // /////////////////////////////  START AUTH  ///////////////////////////////

        Route::group(['middleware' => 'admin.auth'], function () {
            Route::post('logout', [AdminActionController::class, 'logout'])->name('admin.logout');

            Route::get('dashboard', [AdminViewController::class, 'dashboard'])->name('admin.dashboard');
            Route::get('reports', [AdminViewController::class, 'reports'])->name('admin.reports.index');
            // /////////////////////////////  END AUTH  ///////////////////////////////

            // /////////////////////////////  START CASHIERS  ///////////////////////////////
            Route::get('cashiers', [CashierController::class, 'index'])->name('admin.cashiers.index');
            Route::post('cashiers', [CashierController::class, 'store'])->name('admin.cashiers.store');
            Route::get('cashiers/{cashier}/edit', [CashierController::class, 'edit'])->name('admin.cashiers.edit');
            Route::put('cashiers/{cashier}', [CashierController::class, 'update'])->name('admin.cashiers.update');
            Route::patch('cashiers/{cashier}/toggle-status', [CashierController::class, 'toggleStatus'])
                ->name('admin.cashiers.toggle-status');
            Route::get('captains', [CaptainController::class, 'index'])->name('admin.captains.index');
            Route::post('captains', [CaptainController::class, 'store'])->name('admin.captains.store');
            Route::get('captains/{captain}/edit', [CaptainController::class, 'edit'])->name('admin.captains.edit');
            Route::put('captains/{captain}', [CaptainController::class, 'update'])->name('admin.captains.update');
            Route::patch('captains/{captain}/toggle-status', [CaptainController::class, 'toggleStatus'])
                ->name('admin.captains.toggle-status');
            Route::delete('captains/{captain}', [CaptainController::class, 'destroy'])->name('admin.captains.destroy');

            // /////////////////////////////  END CASHIERS  ///////////////////////////////

            // /////////////////////////////  START BILLS  ///////////////////////////////
            Route::get('bills', [BillsController::class, 'index'])->name('admin.bills.index');
            Route::get('bills/{bill}', [BillsController::class, 'show'])->name('admin.bills.show');
            Route::get('bills/{bill}/edit', [BillsController::class, 'edit'])->name('admin.bills.edit');
            Route::put('bills/{bill}', [BillsController::class, 'update'])->name('admin.bills.update');
            Route::delete('bills/{bill}/orders/{order}', [BillsController::class, 'destroyOrder'])
                ->name('admin.bills.orders.destroy');
            // Route::post('orders', [CashierController::class, 'store'])->name('admin.orders.store');
            // Route::get('orders/{cashier}/edit', [CashierController::class, 'edit'])->name('admin.orders.edit');
            // Route::put('orders/{cashier}', [CashierController::class, 'update'])->name('admin.orders.update');
            // Route::patch('orders/{cashier}/toggle-status', [CashierController::class, 'toggleStatus'])
            //     ->name('admin.orders.toggle-status');

            // /////////////////////////////  END BILLS  ///////////////////////////////

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
});

Route::get('test', function () {
    return view('test');
});
