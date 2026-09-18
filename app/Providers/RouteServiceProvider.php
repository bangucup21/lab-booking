<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public const HOME = '/dashboard';

    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {

            // ── API Routes ────────────────────────────────────
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            // ── Web Routes (lab_management) ───────────────────
            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            // ← Tambahan ini saja (3 baris)
            Route::middleware('web')
                ->prefix('finance')
                ->group(base_path('routes/finance.php'));

        });
    }
}