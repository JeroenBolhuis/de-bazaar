<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\SetLocale;
use Illuminate\Auth\Access\AuthorizationException;
use App\Providers\AuthServiceProvider;
use App\Http\Middleware\EnsureContractsAccepted;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            SetLocale::class,
        ]);
        $middleware->alias([
            'contracts.accepted' => EnsureContractsAccepted::class,
        ]);
    })
    ->withProviders([
        AuthServiceProvider::class,
    ])
    ->withExceptions(function (Exceptions $exceptions) {
        // Handle authorization exceptions
        $exceptions->render(function (AuthorizationException $e) {
            return abort(403, $e->getMessage() ?: 'This action is unauthorized.');
        });
    })->create();
