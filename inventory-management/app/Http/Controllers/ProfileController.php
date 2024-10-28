<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

// Kelas ProfileController untuk menangani operasi terkait profil pengguna
class ProfileController extends Controller
{
    // Metode untuk menampilkan profil pengguna
    public function show()
    {
        // Mengambil data pengguna yang sedang login
        $user = Auth::user();
        // Mengembalikan view 'profile.show' dengan data pengguna
        return view('profile.show', compact('user'));
    }

    // Metode untuk menampilkan form edit profil
    public function edit()
    {
        // Mengambil data pengguna yang sedang login
        $user = Auth::user();
        // Mengembalikan view 'profile.edit' dengan data pengguna
        return view('profile.edit', compact('user'));
    }

    // Metode untuk memperbarui profil pengguna
    public function update(Request $request)
    {
        // Mencari pengguna berdasarkan ID yang sedang login
        $user = User::find(Auth::id());

        // Melakukan validasi input
        $request->validate([
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'current_password' => 'required_with:new_password',
            'new_password' => 'nullable|min:8|confirmed',
        ]);

        // Memperbarui username pengguna
        $user->username = $request->username;

        // Jika ada input password baru
        if ($request->filled('new_password')) {
            // Memeriksa apakah password saat ini benar
            if (!Hash::check($request->current_password, $user->password)) {
                // Jika salah, kembali ke halaman sebelumnya dengan pesan error
                return back()->withErrors(['current_password' => 'The current password is incorrect.'])
                             ->with('error', 'The current password is incorrect.');
            }
            // Jika benar, update password dengan yang baru
            $user->password = Hash::make($request->new_password);
        }

        // Menyimpan perubahan ke database
        $user->save();

        // Redirect ke halaman profil dengan pesan sukses
        return redirect()->route('profile.show')->with('success', 'Profile updated successfully.');
    }
}
