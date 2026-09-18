<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class FinanceGuest
{
    public function handle(Request $request, Closure $next): Response
    {
        // Jika sudah login di guard finance, langsung redirect ke dashboard
        if (Auth::guard('finance')->check()) {
            return redirect()->route('finance.dashboard');
        }

        return $next($request);
    }
}
