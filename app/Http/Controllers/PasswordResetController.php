<?php

namespace App\Http\Controllers;

use App\Services\Contracts\PasswordResetServiceInterface;
use Illuminate\Http\Request;

class PasswordResetController extends Controller
{
    public function __construct(private PasswordResetServiceInterface $passwordResetService) {}

    // 1. Menampilkan Form Lupa Sandi (Input Email)
    public function requestForm()
    {
        return view('auth.lupa-kata-sandi');
    }

    // 2. Mengirim Link Reset ke Email
    public function sendEmail(Request $request)
    {
        // 🚨 UPDATE: 'exists:ms_user,email' karena tabel kita ms_user
        $request->validate([
            'email' => 'required|email|exists:ms_user,email',
        ], [
            'email.exists' => 'Alamat email tidak terdaftar di sistem kami.',
        ]);

        $sent = $this->passwordResetService->sendResetLink($request->email);

        return $sent
                    ? back()->with(['status' => 'Link reset kata sandi telah dikirim ke email Anda!'])
                    : back()->withErrors(['email' => 'Gagal mengirim link reset sandi.']);
    }

    // 3. Menampilkan Form Reset Sandi Baru
    public function resetForm(Request $request, $token)
    {
        return view('auth.atur-ulang-kata-sandi', ['token' => $token, 'email' => $request->email]);
    }

    // 4. Memproses Perubahan Kata Sandi Baru
    public function updatePassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:ms_user,email',
            'password' => 'required|min:8|confirmed',
        ]);

        $reset = $this->passwordResetService->reset(
            $request->only('email', 'password', 'password_confirmation', 'token')
        );

        return $reset
                    ? redirect()->route('login')->with('status', 'Kata sandi berhasil diubah! Silakan login kembali.')
                    : back()->withErrors(['email' => ['Reset kata sandi gagal. Silakan minta tautan baru.']]);
    }
}
