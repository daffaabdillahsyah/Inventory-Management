@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Judul halaman dashboard admin -->
    <h2 class="mb-4">Admin Dashboard</h2>
    
    <!-- Baris untuk menampilkan statistik utama -->
    <div class="row mb-4">
        <!-- Kartu untuk menampilkan total produk -->
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">Total Products</h5>
                    <p class="card-text display-4">{{ $totalProducts }}</p>
                </div>
            </div>
        </div>
        <!-- Kartu untuk menampilkan total pesanan -->
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">Total Orders</h5>
                    <p class="card-text display-4">{{ $totalOrders }}</p>
                </div>
            </div>
        </div>
        <!-- Kartu untuk menampilkan total pengguna -->
        <div class="col-md-4">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5 class="card-title">Total Users</h5>
                    <p class="card-text display-4">{{ $totalUsers }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Bagian untuk menampilkan pesanan terbaru -->
    <h3 class="mb-3">Recent Orders</h3>
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <!-- Header tabel -->
            <thead class="thead-dark">
                <tr>
                    <th>Order ID</th>
                    <th>User</th>
                    <th>Total Amount</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <!-- Isi tabel -->
            <tbody>
                <!-- Loop untuk menampilkan setiap pesanan -->
                @foreach($recentOrders as $order)
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->user->username }}</td>
                    <!-- Menampilkan total amount dalam format mata uang Rupiah -->
                    <td>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                    <!-- Menampilkan status pesanan dengan warna yang sesuai -->
                    <td><span class="badge badge-{{ $order->status == 'completed' ? 'success' : 'warning' }}">{{ $order->status }}</span></td>
                    <td>
                        <!-- Tombol untuk melihat detail pesanan -->
                        <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-info">
                            <i class="fas fa-eye"></i> View
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
