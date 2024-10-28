<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * UserController
 * 
 * Controller ini bertanggung jawab untuk mengelola operasi terkait pengguna dalam sistem.
 */
class UserController extends Controller
{
    /**
     * Constructor
     * 
     * Menerapkan middleware 'auth' untuk memastikan bahwa semua metode dalam controller ini
     * hanya dapat diakses oleh pengguna yang sudah terautentikasi.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Menampilkan daftar semua pengguna
     * 
     * Metode ini hanya dapat diakses oleh admin. Jika bukan admin yang mengakses,
     * pengguna akan diarahkan kembali ke dashboard dengan pesan error.
     * 
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        // Memeriksa apakah pengguna yang login adalah admin
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
        }
        // Mengambil semua data pengguna dari database
        $users = User::all();
        // Mengembalikan view 'users.index' dengan data pengguna
        return view('users.index', compact('users'));
    }

    /**
     * Menampilkan detail pengguna tertentu
     * 
     * @param User $user Objek User yang akan ditampilkan detailnya
     * @return \Illuminate\View\View
     */
    public function show(User $user)
    {
        // Mengembalikan view 'users.show' dengan data pengguna yang diminta
        return view('users.show', compact('user'));
    }

    /**
     * Menampilkan form untuk mengedit pengguna
     * 
     * Metode ini hanya dapat diakses oleh admin. Jika bukan admin yang mengakses,
     * pengguna akan diarahkan kembali ke dashboard dengan pesan error.
     * 
     * @param User $user Objek User yang akan diedit
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit(User $user)
    {
        // Memeriksa apakah pengguna yang login adalah admin
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
        }
        // Mengembalikan view 'users.edit' dengan data pengguna yang akan diedit
        return view('users.edit', compact('user'));
    }

    /**
     * Memperbarui data pengguna
     * 
     * Metode ini hanya dapat diakses oleh admin. Jika bukan admin yang mengakses,
     * pengguna akan diarahkan kembali ke dashboard dengan pesan error.
     * 
     * @param Request $request Request object yang berisi data yang akan diupdate
     * @param User $user Objek User yang akan diupdate
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, User $user)
    {
        // Memeriksa apakah pengguna yang login adalah admin
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
        }
        // Melakukan validasi terhadap data yang dikirimkan
        $validatedData = $request->validate([
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,user',
        ]);

        // Memperbarui data pengguna dengan data yang telah divalidasi
        $user->update($validatedData);

        // Mengarahkan kembali ke halaman daftar pengguna dengan pesan sukses
        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    /**
     * Menghapus pengguna
     * 
     * Metode ini hanya dapat diakses oleh admin dan admin tidak dapat menghapus akunnya sendiri.
     * 
     * @param User $user Objek User yang akan dihapus
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $user)
    {
        // Memeriksa apakah pengguna yang login adalah admin
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
        }
        
        // Memeriksa apakah pengguna mencoba menghapus akunnya sendiri
        if (Auth::id() === $user->id) {
            return redirect()->route('users.index')->with('error', 'You cannot delete your own account.');
        }
        
        // Menghapus pengguna dari database
        $user->delete();
        // Mengarahkan kembali ke halaman daftar pengguna dengan pesan sukses
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}
