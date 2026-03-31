<?php

use App\Exceptions\OpenAIAnalysisException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Validation\ValidationException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (OpenAIAnalysisException $e, $request) {
            return response()->json(['error' => 'OpenAI analysis failed. Please try again.'], 422);
        });

        $exceptions->render(function (AuthenticationException $e, $request) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        });

        $exceptions->render(function (ModelNotFoundException $e, $request) {
            return response()->json(['error' => 'Resource not found.'], 404);
        });

        $exceptions->render(function (ValidationException $e, $request) {
            return response()->json(['error' => 'Validation failed.', 'errors' => $e->errors()], 422);
        });
    })->create();
