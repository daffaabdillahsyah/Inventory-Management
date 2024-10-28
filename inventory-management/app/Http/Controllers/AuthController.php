<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Menampilkan formulir login.
     * 
     * Metode ini mengembalikan tampilan 'auth.login' yang berisi formulir login.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Memproses permintaan login.
     * 
     * @param Request $request Request HTTP yang berisi data login
     * 
     * Metode ini melakukan validasi kredensial, mencoba melakukan autentikasi,
     * dan mengarahkan pengguna ke dashboard jika berhasil login.
     * Jika gagal, pengguna akan dikembalikan ke halaman login dengan pesan error.
     */
    public function login(Request $request)
    {
        // Validasi input pengguna
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Mencoba melakukan autentikasi
        if (Auth::attempt($credentials)) {
            // Jika berhasil, regenerasi sesi untuk keamanan
            $request->session()->regenerate();
            // Arahkan pengguna ke halaman yang dimaksud atau ke dashboard
            return redirect()->intended('dashboard');
        }

        // Jika autentikasi gagal, kembalikan ke halaman login dengan pesan error
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    /**
     * Menampilkan formulir registrasi.
     * 
     * Metode ini mengembalikan tampilan 'auth.register' yang berisi formulir registrasi.
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Memproses permintaan registrasi pengguna baru.
     * 
     * @param Request $request Request HTTP yang berisi data registrasi
     * 
     * Metode ini melakukan validasi input, membuat pengguna baru,
     * dan mengarahkan pengguna ke halaman login setelah registrasi berhasil.
     */
    public function register(Request $request)
    {
        // Validasi input pengguna
        $request->validate([
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Membuat pengguna baru
        User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->has('is_admin') ? 'admin' : 'user',
        ]);

        // Arahkan pengguna ke halaman login dengan pesan sukses
        return redirect()->route('login')->with('success', 'Registration successful. Please login.');
    }

    /**
     * Memproses permintaan logout.
     * 
     * @param Request $request Request HTTP
     * 
     * Metode ini melakukan logout pengguna, menginvalidasi sesi,
     * meregenerasi token CSRF, dan mengarahkan pengguna ke halaman utama.
     */
    public function logout(Request $request)
    {
        // Logout pengguna
        Auth::logout();
        // Invalidasi sesi
        $request->session()->invalidate();
        // Regenerasi token CSRF
        $request->session()->regenerateToken();
        // Arahkan pengguna ke halaman utama
        return redirect('/');
    }
}
