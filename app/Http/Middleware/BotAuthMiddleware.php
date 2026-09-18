<?php
// app/Http/Middleware/BotAuthMiddleware.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class BotAuthMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();

        if (!$token || $token !== config('bot.token')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        return $next($request);
    }
}