<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Menampilkan formulir login.
     * 
     * Metode ini bertanggung jawab untuk mengembalikan tampilan formulir login.
     * 
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Memproses permintaan login.
     * 
     * Metode ini menangani proses autentikasi pengguna. Ini melakukan validasi kredensial,
     * mencoba untuk mengautentikasi pengguna, dan mengarahkan mereka ke dashboard jika berhasil.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        // Validasi kredensial yang diberikan oleh pengguna
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Mencoba untuk mengautentikasi pengguna
        if (Auth::attempt($credentials)) {
            // Jika autentikasi berhasil, regenerasi sesi untuk keamanan
            $request->session()->regenerate();
            // Arahkan pengguna ke halaman yang dimaksud atau ke dashboard
            return redirect()->intended('dashboard');
        }

        // Jika autentikasi gagal, kembalikan ke halaman sebelumnya dengan pesan error
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    /**
     * Memproses permintaan logout.
     * 
     * Metode ini menangani proses logout pengguna. Ini menghapus autentikasi pengguna,
     * menginvalidasi sesi, meregenerasi token CSRF, dan mengarahkan pengguna ke halaman utama.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        // Logout pengguna yang saat ini terautentikasi
        Auth::logout();
        // Invalidasi sesi saat ini
        $request->session()->invalidate();
        // Regenerasi token CSRF untuk keamanan
        $request->session()->regenerateToken();
        // Arahkan pengguna ke halaman utama
        return redirect('/');
    }
}
