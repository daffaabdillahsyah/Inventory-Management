@extends('layouts.app')

@section('content')
    <div class="container">
        <!-- Judul halaman untuk mengedit produk -->
        <h2 class="mb-4">Edit Product</h2>
        
        <!-- Form untuk mengedit produk -->
        <!-- action mengarah ke route products.update, method POST dengan @method('PUT'), dan mendukung upload file -->
        <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data">
            <!-- Token CSRF untuk keamanan form -->
            @csrf
            <!-- Metode HTTP yang digunakan adalah PUT untuk update -->
            @method('PUT')
            
            <!-- Input untuk nama produk -->
            <div class="form-group mb-3">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $product->name }}" required>
            </div>
            
            <!-- Textarea untuk deskripsi produk -->
            <div class="form-group mb-3">
                <label for="description">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3" required>{{ $product->description }}</textarea>
            </div>
            
            <!-- Input untuk harga produk dalam Rupiah -->
            <div class="form-group mb-3">
                <label for="price">Price (Rp)</label>
                <input type="number" class="form-control" id="price" name="price" step="1" min="0" value="{{ $product->price }}" required>
            </div>
            
            <!-- Input untuk stok produk -->
            <div class="form-group mb-3">
                <label for="stock">Stock</label>
                <input type="number" class="form-control" id="stock" name="stock" min="0" value="{{ $product->stock }}" required>
            </div>
            
            <!-- Input untuk gambar produk -->
            <div class="form-group mb-3">
                <label for="image">Product Image</label>
                <input type="file" class="form-control-file" id="image" name="image" accept="image/*">
            </div>
            
            <!-- Menampilkan gambar produk yang ada (jika ada) -->
            @if($product->media->isNotEmpty())
                <div class="mb-3">
                    <img src="{{ asset('storage/' . $product->media->first()->file_path) }}" alt="{{ $product->name }}" style="max-width: 200px;">
                </div>
            @endif
            
            <!-- Tombol submit untuk mengirim form -->
            <button type="submit" class="btn btn-primary">Update Product</button>
        </form>
    </div>
@endsection
