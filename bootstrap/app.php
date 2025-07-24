<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

// The incorrect 'use App\Http\Middleware...' statements have been removed.

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        
        $middleware->alias([
            /**
             * CORRECT & OPTIMIZED:
             * We point directly to the full path of the classes within the
             * Laravel framework. This is the official and correct way.
             */
            'auth' => \Illuminate\Auth\Middleware\Authenticate::class,
            'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,

            /**
             * Your custom middleware alias is correct as long as the file exists at:
             * app/Http/Middleware/SpaceRoleMiddleware.php
             */
            'space.role' => \App\Http\Middleware\SpaceRoleMiddleware::class,
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions) {
        // ...
    })->create();