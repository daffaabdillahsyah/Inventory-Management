@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Judul halaman -->
    <h2 class="mb-4">My Orders</h2>
    
    <!-- Cek apakah tidak ada pesanan -->
    @if($orders->isEmpty())
        <!-- Tampilkan pesan jika tidak ada pesanan -->
        <div class="alert alert-info">
            You haven't placed any orders yet. <a href="{{ route('products.user_index') }}">Start shopping now!</a>
        </div>
    @else
        <!-- Loop untuk menampilkan setiap pesanan -->
        @foreach($orders as $order)
            <!-- Card untuk setiap pesanan -->
            <div class="card mb-4 shadow-sm">
                <!-- Header card dengan nomor pesanan dan tanggal -->
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Order #{{ $order->id }}</h5>
                    <span class="badge badge-light">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                </div>
                <div class="card-body">
                    <!-- Informasi ringkas pesanan -->
                    <div class="row mb-3">
                        <!-- Total harga pesanan -->
                        <div class="col-md-4">
                            <strong>Total:</strong> Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                        </div>
                        <!-- Status pesanan dengan warna yang sesuai -->
                        <div class="col-md-4">
                            <strong>Status:</strong> 
                            <span class="badge badge-{{ $order->status == 'completed' ? 'success' : ($order->status == 'pending' ? 'warning' : 'info') }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                        <!-- Jumlah item dalam pesanan -->
                        <div class="col-md-4">
                            <strong>Items:</strong> {{ $order->orderItems->count() }}
                        </div>
                    </div>
                    <!-- Tabel detail item pesanan -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>Product</th>
                                    <th>Image</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Loop untuk menampilkan setiap item dalam pesanan -->
                                @foreach($order->orderItems as $item)
                                    <tr>
                                        <!-- Nama produk -->
                                        <td>{{ $item->product->name }}</td>
                                        <!-- Gambar produk (jika ada) -->
                                        <td>
                                            @if($item->product->media->isNotEmpty())
                                                <img src="{{ asset('storage/' . $item->product->media->first()->file_path) }}" 
                                                     alt="{{ $item->product->name }}" 
                                                     class="img-thumbnail" style="max-width: 50px; max-height: 50px;">
                                            @else
                                                <span class="text-muted">No Image</span>
                                            @endif
                                        </td>
                                        <!-- Jumlah item yang dipesan -->
                                        <td>{{ $item->quantity }}</td>
                                        <!-- Harga per item -->
                                        <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                        <!-- Subtotal (harga * jumlah) -->
                                        <td>Rp {{ number_format($item->quantity * $item->price, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
</div>
@endsection
