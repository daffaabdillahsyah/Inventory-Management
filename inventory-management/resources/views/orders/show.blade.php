@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Judul halaman -->
    <h2>Order Details</h2>
    
    <!-- Card untuk menampilkan detail pesanan -->
    <div class="card">
        <div class="card-body">
            <!-- Menampilkan nomor pesanan -->
            <h5 class="card-title">Order #{{ $order->id }}</h5>
            <!-- Menampilkan username pengguna yang melakukan pesanan -->
            <p class="card-text">User: {{ $order->user->username }}</p>
            <!-- Menampilkan total jumlah pesanan dalam format Rupiah -->
            <p class="card-text">Total Amount: Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
            <!-- Menampilkan status pesanan dengan huruf kapital di awal -->
            <p class="card-text">Status: {{ ucfirst($order->status) }}</p>
            <!-- Menampilkan tanggal pesanan dalam format d/m/Y H:i -->
            <p class="card-text">Date: {{ $order->created_at->format('d/m/Y H:i') }}</p>
        </div>
    </div>

    <!-- Judul untuk tabel item pesanan -->
    <h3 class="mt-4">Order Items</h3>
    
    <!-- Tabel untuk menampilkan daftar item pesanan -->
    <table class="table">
        <thead>
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <!-- Loop untuk menampilkan setiap item pesanan -->
            @foreach($order->orderItems as $item)
            <tr>
                <!-- Menampilkan nama produk -->
                <td>{{ $item->product->name }}</td>
                <!-- Menampilkan jumlah item yang dipesan -->
                <td>{{ $item->quantity }}</td>
                <!-- Menampilkan harga per item dalam format Rupiah -->
                <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                <!-- Menampilkan subtotal (jumlah * harga) dalam format Rupiah -->
                <td>Rp {{ number_format($item->quantity * $item->price, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Tombol untuk kembali ke halaman daftar pesanan -->
    <a href="{{ route('orders.index') }}" class="btn btn-secondary">Back to Orders</a>
</div>
@endsection
