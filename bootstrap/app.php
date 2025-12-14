<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\HandleCors; // Importação essencial para o CORS

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

        // Aplica o middleware HandleCors (que lê o config/cors.php)
        // O método 'prepend' garante que o CORS seja a primeira coisa a rodar
        // no grupo 'api', antes de qualquer outra verificação.
        $middleware->api(prepend: [
            HandleCors::class,
        ]);

        // Se você tiver outros middlewares para a API (como SubstituteBindings),
        // eles podem ser adicionados assim (opcional, mas recomendado):
        /*
        $middleware->api(append: [
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);
        */

        // Se você precisar de algum middleware global, pode adicionar aqui:
        // $middleware->alias([
        //     'can' => \Illuminate\Auth\Middleware\Authorize::class,
        // ]);

    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
