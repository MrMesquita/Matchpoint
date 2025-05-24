<?php

use App\Http\Middleware\TraceIdMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

use function App\Exceptions\handleExceptions;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        if (env('APP_ENV') == 'production') {
            $middleware->appendToGroup('api', TraceIdMiddleware::class);
        }
    })
    ->withExceptions(function (Exceptions $exceptions) {
        return handleExceptions($exceptions);
    })
    ->create();
