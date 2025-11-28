<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class AuthController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard.home');
        }

        return view('auth.login');
    }

    /**
     * Handle authentication attempt.
     */
    public function authenticate(LoginRequest $request)
    {
        $login = $request->input('login');
        $password = $request->input('password');

        // Determine if login is email or username
        $credentials = filter_var($login, FILTER_VALIDATE_EMAIL)
            ? ['email' => $login, 'password' => $password]
            : ['username' => $login, 'password' => $password];

        // Add is_active check
        $credentials['is_active'] = true;

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Check if user has admin or teacher role
            if (! $user->hasAnyRole(['admin', 'teacher'])) {
                Auth::logout();
                Alert::error('Akses Ditolak', 'Hanya admin dan guru yang dapat mengakses halaman ini.');

                return redirect()->route('auth.login');
            }

            // Initialize history session
            session()->put('history', []);

            Alert::success('Login Berhasil', "Selamat datang, {$user->name}!");

            return redirect()->intended(route('dashboard.home'));
        }

        Alert::error('Login Gagal', 'Email/username atau password salah, atau akun Anda tidak aktif.');

        return back()->withInput($request->only('login'));
    }

    /**
     * Handle logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        Alert::info('Logout Berhasil', 'Anda telah keluar dari sistem.');

        return redirect()->route('auth.login');
    }
}
