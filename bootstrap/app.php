<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\SessionCheck;
use App\Http\Middleware\AlreadyLoggedIn;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
           $middleware->web(append: [
            AlreadyLoggedIn::class,
        ]);

         $middleware->alias([
            'session_check' => SessionCheck::class, // Register your custom middleware here
        ]);
         $middleware->alias([
            'session_check' => AlreadyLoggedIn::class, // Register your custom middleware here
        ]);
        
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
