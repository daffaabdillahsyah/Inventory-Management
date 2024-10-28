@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Judul halaman untuk manajemen pengguna -->
    <h2 class="mb-4">Manage Users</h2>
    
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

    <!-- Kartu untuk menampilkan tabel pengguna -->
    <div class="card">
        <div class="card-body">
            <!-- Tabel responsif untuk daftar pengguna -->
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <!-- Header tabel -->
                    <thead class="thead-dark">
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <!-- Isi tabel -->
                    <tbody>
                        <!-- Loop untuk setiap pengguna -->
                        @foreach($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->username }}</td>
                            <td>{{ $user->email }}</td>
                            <!-- Menampilkan peran pengguna dengan badge -->
                            <td>
                                <span class="badge badge-{{ $user->role == 'admin' ? 'primary' : 'secondary' }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <!-- Aksi untuk setiap pengguna -->
                            <td>
                                <!-- Tombol untuk mengedit pengguna -->
                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <!-- Tombol untuk menghapus pengguna (tidak ditampilkan untuk pengguna yang sedang login) -->
                                @if(Auth::id() !== $user->id)
                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display: inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this user?')">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                @endif
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
