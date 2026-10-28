<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Tampilkan halaman login khusus teknisi & admin workshop.
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('admin.teknisi');
        }

        return view('auth.login-teknisi');
    }

    /**
     * Proses autentikasi staf teknisi via ID Karyawan atau Email.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'tech_id' => 'required|string',
            'password' => 'required|string',
        ]);

        $loginInput = $credentials['tech_id'];

        // Cek login via ID Karyawan (tech_code) atau Email
        $field = filter_var($loginInput, FILTER_VALIDATE_EMAIL) ? 'email' : 'tech_code';

        if (Auth::attempt([$field => $loginInput, 'password' => $credentials['password'], 'is_active' => true], $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('admin.teknisi'))
                ->with('success', 'Selamat datang di Meja Kerja Workshop SerComp, ' . Auth::user()->name . '!');
        }

        return back()->withErrors([
            'tech_id' => 'Kredensial ID Teknisi atau kata sandi tidak cocok.',
        ])->onlyInput('tech_id');
    }

    /**
     * Logout teknisi dari sistem workshop.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('teknisi.login')
            ->with('info', 'Anda telah berhasil keluar dari sistem workshop.');
    }
}
