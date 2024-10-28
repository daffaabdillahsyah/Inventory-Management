@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Judul halaman untuk menampilkan detail produk -->
    <h2>Product Details</h2>
    
    <!-- Card untuk menampilkan informasi produk -->
    <div class="card">
        <div class="card-body">
            <!-- Nama produk -->
            <h5 class="card-title">{{ $product->name }}</h5>
            
            <!-- Deskripsi produk -->
            <p class="card-text"><strong>Description:</strong> {{ $product->description }}</p>
            
            <!-- Harga produk yang sudah diformat -->
            <p class="card-text"><strong>Price:</strong> {{ $product->price_formatted }}</p>
            
            <!-- Jumlah stok produk -->
            <p class="card-text"><strong>Stock:</strong> {{ $product->stock }}</p>
            
            <!-- Menampilkan gambar produk jika ada -->
            @if($product->media->isNotEmpty())
                <div class="mt-3">
                    <strong>Product Image:</strong><br>
                    <!-- Menampilkan gambar produk dari penyimpanan -->
                    <img src="{{ asset('storage/' . $product->media->first()->file_path) }}" alt="{{ $product->name }}" style="max-width: 300px; max-height: 300px;">
                </div>
            @else
                <!-- Menampilkan gambar default jika tidak ada gambar produk -->
                <div class="mt-3">
                    <strong>Product Image:</strong><br>
                    <img src="{{ asset('images/no-image.jpg') }}" alt="No Image Available" style="max-width: 300px; max-height: 300px;">
                </div>
            @endif
        </div>
    </div>
    
    <!-- Tombol-tombol aksi -->
    <div class="mt-3">
        <!-- Tombol untuk mengedit produk -->
        <a href="{{ route('products.edit', $product) }}" class="btn btn-primary">Edit</a>
        
        <!-- Tombol untuk kembali ke daftar produk -->
        <a href="{{ route('products.index') }}" class="btn btn-secondary">Back to List</a>
        
        <!-- Form untuk menghapus produk -->
        <form action="{{ route('products.destroy', $product) }}" method="POST" style="display: inline-block;">
            @csrf
            @method('DELETE')
            <!-- Tombol untuk menghapus produk dengan konfirmasi -->
            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this product?')">Delete</button>
        </form>
    </div>
</div>
@endsection
