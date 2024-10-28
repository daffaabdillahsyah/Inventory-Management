@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Judul halaman -->
    <h2 class="mb-4">Manage Products</h2>
    
    <!-- Menampilkan pesan sukses jika ada -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    
    <!-- Menampilkan pesan error jika ada -->
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <!-- Tombol untuk membuat produk baru, ekspor, dan impor produk -->
    <div class="mb-3">
        <a href="{{ route('products.create') }}" class="btn btn-primary">Add New Product</a>
        <a href="{{ route('products.export') }}" class="btn btn-success">Export Products</a>
        <a href="{{ route('products.import.form') }}" class="btn btn-primary">Import Products</a>
    </div>

    <!-- Kartu untuk menampilkan tabel produk -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <!-- Tabel produk -->
                <table class="table table-bordered table-striped">
                    <!-- Header tabel -->
                    <thead class="thead-dark">
                        <tr>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <!-- Isi tabel -->
                    <tbody>
                        <!-- Loop untuk menampilkan setiap produk -->
                        @foreach($products as $product)
                            <tr>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->price }}</td>
                                <td>{{ $product->stock }}</td>
                                <td>
                                    <!-- Tombol untuk melihat detail produk -->
                                    <a href="{{ route('products.show', $product) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                    <!-- Tombol untuk mengedit produk -->
                                    <a href="{{ route('products.edit', $product) }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <!-- Form untuk menghapus produk -->
                                    <form action="{{ route('products.destroy', $product) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <!-- Tombol untuk menghapus produk dengan konfirmasi -->
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this product?')">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
