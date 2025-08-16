<?php

use App\Exceptions\Handler;
use App\Http\Middleware\SignatureMiddleware;
use App\Http\Middleware\TransformInput;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\HandleCors;
use Laravel\Passport\Http\Middleware\CheckClientCredentials;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
        $middleware->alias([
            'signature' => SignatureMiddleware::class,
            'transform.input' => TransformInput::class,
            'cors' => HandleCors::class
        ]);

        $middleware->api([
            'signature:X-Application-name',
            'cors'
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //

    })->create();
