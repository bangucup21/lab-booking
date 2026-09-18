<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckLabAccess
{
    /**
     * Untuk teknisi: hanya bisa akses lab yang ditugaskan.
     * Admin & super_admin bebas akses semua lab.
     *
     * Ambil lab_id dari route parameter atau request input.
     * Usage: ->middleware('lab.access')
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Admin & super_admin: akses penuh
        if ($user->hasFullAccess()) {
            return $next($request);
        }

        // Teknisi: cek assignment
        if ($user->isTeknisi()) {
            $labId = (int) (
                $request->route('lab') ??
                $request->route('resource') ??
                $request->route('lab_id') ??
                $request->input('lab_id') ??
                $request->input('resource_id')
            );

            if ($labId && !$user->isAssignedToLab($labId)) {
                abort(403, 'Anda tidak ditugaskan di lab ini.');
            }

            return $next($request);
        }

        abort(403);
    }
}
