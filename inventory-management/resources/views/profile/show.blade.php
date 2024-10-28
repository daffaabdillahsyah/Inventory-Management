@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Judul halaman untuk menampilkan profil pengguna -->
    <h2>User Profile</h2>
    <!-- Kartu untuk menampilkan informasi profil -->
    <div class="card">
        <div class="card-body">
            <!-- Menampilkan username pengguna -->
            <p><strong>Username:</strong> {{ $user->username }}</p>
            <!-- Menampilkan alamat email pengguna -->
            <p><strong>Email:</strong> {{ $user->email }}</p>
            <!-- Menampilkan peran pengguna dengan huruf pertama kapital -->
            <p><strong>Role:</strong> {{ ucfirst($user->role) }}</p>
            <!-- Tombol untuk mengarahkan ke halaman edit profil -->
            <a href="{{ route('profile.edit') }}" class="btn btn-primary">Edit Profile</a>
        </div>
    </div>
</div>
@endsection