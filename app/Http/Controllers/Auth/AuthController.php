<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Resource;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

class AuthController extends Controller
{
    public function showLogin()
    {
        // Cache stats 60 detik — tidak perlu query setiap halaman login dibuka
        $stats = Cache::remember('login_page_stats', 60, function () {
            return [
                'labs'    => Resource::count(),
                'pending' => Booking::where('status', 'pending')->count(),
                'today'   => Booking::whereDate('created_at', today())->count(),
            ];
        });

        return view('auth.login', compact('stats'));
    }

    public function login(Request $request)
    {
        // Validasi input — tambah max length untuk mencegah long string attack
        $request->validate([
            'username' => 'required|string|max:100',
            'password' => 'required|string|max:200',
        ], [
            'username.required' => 'Username wajib diisi.',
            'username.max'      => 'Username tidak valid.',
            'password.required' => 'Password wajib diisi.',
            'password.max'      => 'Password tidak valid.',
        ]);

        // Rate limiting — max 5 percobaan per menit per username+IP
        // IP diambil dari request yang sudah difilter TrustProxies
        $key = 'login.' . sha1($request->input('username') . '|' . $request->ip());

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);

            // Audit log: rate limit tercapai
            Log::warning('Login rate limit reached', [
                'username' => $request->input('username'),
                'ip'       => $request->ip(),
                'ua'       => $request->userAgent(),
            ]);

            return back()->withErrors([
                'username' => "Terlalu banyak percobaan. Coba lagi dalam {$seconds} detik.",
            ])->withInput($request->only('username'));
        }

        // Cek user
        $user = User::where('username', $request->username)
                    ->where('is_active', 1)
                    ->first();

        if (!$user || !Hash::check($request->password, $user->password_hash)) {
            RateLimiter::hit($key, 60);

            // Audit log: login gagal
            Log::warning('Failed login attempt', [
                'username' => $request->input('username'),
                'ip'       => $request->ip(),
                'ua'       => $request->userAgent(),
                'reason'   => !$user ? 'user_not_found' : 'wrong_password',
            ]);

            return back()->withErrors([
                'username' => 'Username atau password salah.',
            ])->withInput($request->only('username'));
        }

        // Login berhasil
        RateLimiter::clear($key);
        Cache::forget('login_page_stats');

        // Rotate remember token — token lama yang mungkin bocor tidak bisa dipakai lagi
        $user->forceFill(['remember_token' => null])->save();

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        // Audit log: login berhasil
        Log::info('Successful login', [
            'user_id'  => $user->id,
            'username' => $user->username,
            'ip'       => $request->ip(),
            'ua'       => $request->userAgent(),
        ]);

        return redirect()->intended(route('dashboard'));
    }

    public function logout(Request $request)
    {
        // Audit log: logout
        Log::info('User logged out', [
            'user_id'  => Auth::id(),
            'username' => Auth::user()?->username,
            'ip'       => $request->ip(),
        ]);

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
