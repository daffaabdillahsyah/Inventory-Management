<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;

class HomeController extends Controller
{
    /**
     * Membuat instance controller baru.
     *
     * @return void
     */
    public function __construct()
    {
        // Menerapkan middleware 'auth' untuk memastikan hanya pengguna yang sudah login yang dapat mengakses
        $this->middleware('auth');
    }

    /**
     * Menampilkan dashboard aplikasi.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Mendapatkan informasi pengguna yang sedang login
        $user = Auth::user();
        
        // Memeriksa apakah pengguna memiliki peran admin
        if ($user->role === 'admin') {
            // Jika pengguna adalah admin, siapkan data untuk dashboard admin
            
            // Menghitung total produk dalam database
            $totalProducts = Product::count();
            
            // Menghitung total pesanan dalam database
            $totalOrders = Order::count();
            
            // Menghitung total pengguna dalam database
            $totalUsers = User::count();
            
            // Mengambil 5 pesanan terbaru beserta informasi pengguna terkait
            $recentOrders = Order::with('user')->latest()->take(5)->get();
            
            // Menampilkan view dashboard admin dengan data yang telah disiapkan
            return view('admin.dashboard', compact('user', 'totalProducts', 'totalOrders', 'totalUsers', 'recentOrders'));
        } else {
            // Jika pengguna bukan admin, siapkan data untuk dashboard pengguna biasa
            
            // Menghitung jumlah pesanan yang dimiliki oleh pengguna
            $userOrders = Order::where('user_id', $user->id)->count();
            
            // Mengambil pesanan terakhir dari pengguna
            $lastOrder = Order::where('user_id', $user->id)->latest()->first();
            
            // Mengambil semua produk yang memiliki stok lebih dari 0
            $products = Product::where('stock', '>', 0)->get();
            
            // Menampilkan view dashboard pengguna biasa dengan data yang telah disiapkan
            return view('dashboard', compact('user', 'userOrders', 'lastOrder', 'products'));
        }
    }
}
