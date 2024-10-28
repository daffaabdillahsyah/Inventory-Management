@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Judul halaman untuk menampilkan semua produk -->
    <h2 class="mb-4">All Products</h2>
    
    <!-- Cek apakah tidak ada produk yang tersedia -->
    @if($products->isEmpty())
        <!-- Tampilkan pesan jika tidak ada produk -->
        <div class="alert alert-info">
            No products available at the moment.
        </div>
    @else
        <!-- Jika ada produk, tampilkan dalam bentuk grid -->
        <div class="row">
            <!-- Loop melalui setiap produk -->
            @foreach($products as $product)
                <!-- Setiap produk ditampilkan dalam kolom dengan lebar 4 (untuk layar medium) -->
                <div class="col-md-4 mb-4">
                    <!-- Card untuk menampilkan informasi produk -->
                    <div class="card h-100 shadow-sm">
                        <!-- Cek apakah produk memiliki gambar -->
                        @if($product->media->isNotEmpty())
                            <!-- Jika ada, tampilkan gambar produk -->
                            <img src="{{ asset('storage/' . $product->media->first()->file_path) }}" 
                                 class="card-img-top" 
                                 alt="{{ $product->name }}"
                                 style="height: 200px; object-fit: cover;">
                        @else
                            <!-- Jika tidak ada gambar, tampilkan placeholder -->
                            <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center" 
                                 style="height: 200px;">
                                <span class="text-white">No Image</span>
                            </div>
                        @endif
                        <!-- Bagian body dari card -->
                        <div class="card-body d-flex flex-column">
                            <!-- Judul card (nama produk) -->
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <!-- Deskripsi produk (dibatasi hingga 100 karakter) -->
                            <p class="card-text flex-grow-1">{{ Str::limit($product->description, 100) }}</p>
                            <!-- Informasi tambahan produk -->
                            <div class="mt-auto">
                                <!-- Harga produk -->
                                <p class="card-text">
                                    <strong>Price:</strong> 
                                    <span class="text-primary">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                </p>
                                <!-- Stok produk -->
                                <p class="card-text">
                                    <strong>Stock:</strong> 
                                    <!-- Tampilkan badge hijau jika stok tersedia, merah jika habis -->
                                    <span class="badge badge-{{ $product->stock > 0 ? 'success' : 'danger' }}">
                                        {{ $product->stock > 0 ? $product->stock . ' available' : 'Out of stock' }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
