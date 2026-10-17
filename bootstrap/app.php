<?php

use App\Http\Middleware\CheckAdminToLogin;
use App\Http\Middleware\EnsureTableSession;
use App\Http\Middleware\RedirectCashierToLogin;
use App\Http\Middleware\RedirectAdminToLogin;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        channels: __DIR__.'/../routes/channels.php',
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withCommands()
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin.auth' => RedirectAdminToLogin::class,
            'cashier.auth' => RedirectCashierToLogin::class,
            'check.auth' => CheckAdminToLogin::class,
            'table.session' => EnsureTableSession::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
