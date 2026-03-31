<?php

namespace App\Providers;

use App\Models\Analysis;
use App\Policies\AnalysisPolicy;
use App\Services\OpenAIService;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;
use OpenAI\Client;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(OpenAIService::class, function ($app) {
            return new OpenAIService($app->make(Client::class));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Analysis::class, AnalysisPolicy::class);

        // Rate limiting
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip())
                ->response(function (Request $request, array $headers) {
                    return Response::json(['error' => 'API rate limit exceeded.'], 429, $headers);
                });
        });

        RateLimiter::for('analyze', function (Request $request) {
            return Limit::perHour(10)->by($request->user()?->id ?: $request->ip())
                ->response(function (Request $request, array $headers) {
                    return Response::json(['error' => 'Analysis rate limit exceeded.'], 429, $headers);
                });
        });

        // Response macros
        Response::macro('success', function ($data = null, $message = '', $code = 200) {
            return Response::json([
                'success' => true,
                'data' => $data,
                'message' => $message,
            ], $code);
        });

        Response::macro('error', function ($message, $code = 400) {
            return Response::json([
                'success' => false,
                'message' => $message,
            ], $code);
        });
    }
}
