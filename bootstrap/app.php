<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        
        // 1. Trust Ngrok: This fixes "http vs https" issues (broken images/assets)
        $middleware->trustProxies(at: '*');

        // 2. Fix Mobile Sync: This allows the Phone (Ngrok) to send data to the PC (Localhost)
        // without getting blocked by CSRF security checks.
        $middleware->validateCsrfTokens(except: [
            'pos/*',               // Allow all POS routes
            'pos/remote-push',     // Allow phone to send barcode
            'pos/mobile-command-poll', // Allow phone to ask for commands
            'pos/check-transaction',
            'pos/generate-khqr'
        ]);

        // 3. Your existing Role Middleware
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();