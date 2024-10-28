@extends('layouts.app')

@section('content')
    <div class="container">
        <!-- Judul halaman untuk menambahkan produk baru -->
        <h2 class="mb-4">Add New Product</h2>
        
        <!-- Form untuk menambahkan produk baru -->
        <!-- action mengarah ke route products.store, method POST, dan mendukung upload file -->
        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
            <!-- Token CSRF untuk keamanan form -->
            @csrf
            
            <!-- Input untuk nama produk -->
            <div class="form-group mb-3">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            
            <!-- Textarea untuk deskripsi produk -->
            <div class="form-group mb-3">
                <label for="description">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
            </div>
            
            <!-- Input untuk harga produk dalam Rupiah -->
            <div class="form-group mb-3">
                <label for="price">Price (Rp)</label>
                <input type="number" class="form-control" id="price" name="price" step="1" min="0" required>
            </div>
            
            <!-- Input untuk stok produk -->
            <div class="form-group mb-3">
                <label for="stock">Stock</label>
                <input type="number" class="form-control" id="stock" name="stock" min="0" required>
            </div>
            
            <!-- Input untuk gambar produk -->
            <div class="form-group mb-3">
                <label for="image">Product Image</label>
                <input type="file" class="form-control-file" id="image" name="image" accept="image/*">
            </div>
            
            <!-- Tombol submit untuk mengirim form -->
            <button type="submit" class="btn btn-primary">Create Product</button>
        </form>
    </div>
@endsection
