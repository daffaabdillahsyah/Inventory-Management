<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

// Kelas AdminMiddleware digunakan untuk mengontrol akses ke rute-rute yang memerlukan hak akses admin
class AdminMiddleware
{
    // Metode handle dijalankan setiap kali middleware ini dipanggil
    public function handle(Request $request, Closure $next)
    {
        // Mencatat informasi peran pengguna saat ini ke dalam log
        // Jika pengguna belum login, akan dicatat sebagai 'not logged in'
        Log::info('AdminMiddleware: User role', ['role' => Auth::user()->role ?? 'not logged in']);
        
        // Memeriksa apakah pengguna sudah login dan memiliki peran admin
        if (Auth::check() && Auth::user()->role === 'admin') {
            // Jika pengguna adalah admin, izinkan akses ke rute berikutnya
            return $next($request);
        }
        
        // Jika pengguna bukan admin, catat peringatan ke dalam log
        Log::warning('AdminMiddleware: Access denied');
        // Redirect pengguna ke halaman dashboard dengan pesan error
        return redirect('dashboard')->with('error', 'You do not have admin access.');
    }
}
