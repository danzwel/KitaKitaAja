<?php

use App\Http\Middleware\Admin\EnsureAdminIsAuthenticated;
use App\Http\Middleware\Admin\RedirectIfAdminAuthenticated;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin.auth' => EnsureAdminIsAuthenticated::class,
            'admin.guest' => RedirectIfAdminAuthenticated::class,
            'intern.password.change' => \App\Http\Middleware\EnsureInternHasChangedPassword::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();