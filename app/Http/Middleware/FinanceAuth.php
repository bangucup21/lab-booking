<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class FinanceAuth
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // Cek apakah sudah login via guard 'finance'
        if (!Auth::guard('finance')->check()) {
            return redirect()
                ->route('finance.login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::guard('finance')->user();

        // Cek apakah akun masih aktif
        if (!$user->is_active) {
            Auth::guard('finance')->logout();
            return redirect()
                ->route('finance.login')
                ->with('error', 'Akun Anda telah dinonaktifkan.');
        }

        // Cek role jika parameter diberikan
        // Contoh penggunaan: middleware('finance.auth:admin')
        if (!empty($roles) && !in_array($user->role, $roles)) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}
