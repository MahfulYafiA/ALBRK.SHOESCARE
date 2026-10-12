<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use App\Services\Contracts\AuthServiceInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    public function __construct(
        private AuthServiceInterface $authService
    ) {}

    /**
     * Show login form
     */
    public function showLogin()
    {
        return view('auth.masuk');
    }

    /**
     * Handle login
     */
    public function login(LoginRequest $request): RedirectResponse
    {
        $result = $this->authService->attemptLogin(
            $request->validated('email'),
            $request->validated('password'),
        );
        if (! $result) {
            return back()->with('error', 'Email atau password salah.')->withInput($request->only('email'));
        }

        if ($result['inactive'] ?? false) {
            return back()->with('error', 'Akun Anda nonaktif. Hubungi administrator.')->withInput($request->only('email'));
        }

        $request->session()->regenerate();

        return $this->redirectForUser($result['user'], 'Selamat datang, '.$result['user']->nama.'!');
    }

    /**
     * Show register form
     */
    public function showRegister()
    {
        return view('auth.daftar');
    }

    /**
     * Handle registration
     */
    public function register(RegisterRequest $request): RedirectResponse
    {
        $result = $this->authService->register($request->validated());
        if (! $result['user']) {
            return back()->with('error', 'Registrasi gagal. Silakan coba lagi.')->withInput();
        }

        $request->session()->regenerate();

        return redirect()->route('pelanggan.dashboard')->with('success', 'Registrasi berhasil! Selamat datang, '.$result['user']->nama.'!');
    }

    /**
     * Redirect to Google
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle Google callback
     */
    public function handleGoogleCallback(Request $request): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $userData = [
                'id' => $googleUser->getId(),
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
            ];

            $result = $this->authService->handleGoogleCallback($userData);

            if ($result['inactive'] ?? false) {
                return redirect()->route('login')->with('error', $result['message']);
            }

            $request->session()->regenerate();

            return $this->redirectForUser($result['user'], $result['message']);
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Login dengan Google gagal. Silakan coba lagi.');
        }
    }

    /**
     * Handle logout
     */
    public function logout(Request $request): RedirectResponse
    {
        $this->authService->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('landing')->with('success', 'Anda telah logout.');
    }

    private function redirectForUser(User $user, string $message): RedirectResponse
    {
        $route = match (true) {
            $user->isSuperAdmin() => 'superadmin.dashboard',
            $user->isAdmin() => 'admin.dashboard',
            default => 'pelanggan.dashboard',
        };

        return redirect()->route($route)->with('success', $message);
    }
}
