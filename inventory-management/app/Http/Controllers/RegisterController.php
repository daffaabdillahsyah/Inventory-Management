<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

// Kelas RegisterController yang mewarisi dari kelas Controller
class RegisterController extends Controller
{
    // Metode untuk menampilkan formulir pendaftaran
    public function showRegistrationForm()
    {
        // Mengembalikan tampilan 'auth.register'
        return view('auth.register');
    }

    // Metode untuk memproses pendaftaran pengguna
    public function register(Request $request)
    {
        // Memvalidasi data yang dikirimkan oleh pengguna
        $validatedData = $request->validate([
            'username' => 'required|string|max:255|unique:users', // Username harus diisi, berupa string, maksimal 255 karakter, dan unik dalam tabel users
            'email' => 'required|string|email|max:255|unique:users', // Email harus diisi, berupa string, format email valid, maksimal 255 karakter, dan unik dalam tabel users
            'password' => 'required|string|min:8|confirmed', // Password harus diisi, berupa string, minimal 8 karakter, dan dikonfirmasi
        ]);

        // Membuat pengguna baru dalam database
        User::create([
            'username' => $validatedData['username'], // Menggunakan username yang telah divalidasi
            'email' => $validatedData['email'], // Menggunakan email yang telah divalidasi
            'password' => Hash::make($validatedData['password']), // Mengenkripsi password sebelum disimpan
            'role' => $request->has('is_admin') ? 'admin' : 'user', // Menetapkan peran pengguna berdasarkan ada tidaknya input 'is_admin'
        ]);

        // Mengarahkan pengguna ke halaman login dengan pesan sukses
        return redirect()->route('login')->with('success', 'Registration successful. Please login.');
    }
}
