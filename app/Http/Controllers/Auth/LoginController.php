<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\View\View;

final class LoginController extends Controller
{
    private const int MAX_ATTEMPTS = 5;

    private const int DECAY_SECONDS = 60;

    /**
     * Menampilkan formulir masuk kedinasan.
     */
    public function showLoginForm(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    /**
     * Memproses autentikasi pengguna dengan pembatasan laju (Rate Limiting).
     */
    public function login(LoginRequest $request): RedirectResponse
    {
        $throttleKey = $this->throttleKey($request);

        if (RateLimiter::tooManyAttempts($throttleKey, self::MAX_ATTEMPTS)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            return back()
                ->withInput($request->only('email', 'remember'))
                ->withErrors([
                    'email' => __('persuratan.auth.throttle', ['seconds' => $seconds]),
                ]);
        }

        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        if (! Auth::attempt($credentials, $remember)) {
            RateLimiter::hit($throttleKey, self::DECAY_SECONDS);

            return back()
                ->withInput($request->only('email', 'remember'))
                ->withErrors([
                    'email' => __('persuratan.auth.login_failed'),
                ]);
        }

        RateLimiter::clear($throttleKey);
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'))
            ->with('status', __('persuratan.auth.login_success'));
    }

    /**
     * Mengakhiri sesi masuk kedinasan (Logout).
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('status', __('persuratan.auth.logout_success'));
    }

    /**
     * Kunci throttle berbasis kombinasi surel dan alamat IP pengirim.
     */
    private function throttleKey(Request $request): string
    {
        return Str::transliterate(
            Str::lower((string) $request->input('email')).'|'.$request->ip()
        );
    }
}
