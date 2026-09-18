{{-- resources/views/auth/login.blade.php --}}
<x-auth-layout :stats="$stats">

    {{-- Error --}}
    @if ($errors->any())
        <div class="error-box" id="error-box">
            <svg viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <span>{{ $errors->first() }}</span>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" novalidate>
        @csrf

        {{-- Username --}}
        <div class="form-group">
            <div class="float-label input-wrap">
                <input
                    type="text"
                    id="username"
                    name="username"
                    value="{{ old('username') }}"
                    class="form-input {{ $errors->has('username') ? 'is-error' : '' }}"
                    placeholder="username"
                    autocomplete="username"
                    required autofocus
                >
                <label for="username">Username</label>
            </div>
        </div>

        {{-- Password --}}
        <div class="form-group">
            <div class="float-label input-wrap">
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="form-input has-icon {{ $errors->has('password') ? 'is-error' : '' }}"
                    placeholder="password"
                    autocomplete="current-password"
                    required
                >
                <label for="password">Password</label>
                <button
                    type="button"
                    class="toggle-pass"
                    onclick="togglePassword()"
                    aria-label="Tampilkan atau sembunyikan password"
                >
                    <svg id="icon-eye" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    <svg id="icon-eye-off" viewBox="0 0 24 24" stroke-width="1.8" style="display:none">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                    </svg>
                </button>
            </div>
            {{-- Caps Lock warning --}}
            <div class="caps-warning" id="caps-warning">
                <svg viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                </svg>
                Caps Lock aktif
            </div>
        </div>

        {{-- Remember me --}}
        <div class="form-footer">
            <label class="remember-label">
                <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                Ingat saya
            </label>
        </div>

        {{-- Submit --}}
        <button type="submit" class="btn-login">
            <span class="spinner"></span>
            <span class="btn-text">Masuk ke Sistem</span>
            <svg class="btn-arrow" viewBox="0 0 24 24" stroke-width="2.2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
            </svg>
        </button>

    </form>

    <script>
        function togglePassword() {
            const input      = document.getElementById('password');
            const iconEye    = document.getElementById('icon-eye');
            const iconEyeOff = document.getElementById('icon-eye-off');
            const isHidden   = input.type === 'password';

            input.type               = isHidden ? 'text'    : 'password';
            iconEye.style.display    = isHidden ? 'none'    : '';
            iconEyeOff.style.display = isHidden ? ''        : 'none';
        }
    </script>

</x-auth-layout>
