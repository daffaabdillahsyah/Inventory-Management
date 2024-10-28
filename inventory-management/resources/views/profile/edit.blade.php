@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Judul halaman untuk mengedit profil -->
    <h2>Edit Profile</h2>
    
    <!-- Form untuk mengupdate profil pengguna -->
    <form action="{{ route('profile.update') }}" method="POST">
        <!-- Token CSRF untuk keamanan form -->
        @csrf
        <!-- Metode HTTP yang digunakan adalah PUT untuk update -->
        @method('PUT')
        
        <!-- Input untuk username -->
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" class="form-control" id="username" name="username" value="{{ $user->username }}" required>
        </div>
        
        <!-- Input untuk email (tidak dapat diubah) -->
        <div class="form-group">
            <label for="email">Email (cannot be changed)</label>
            <input type="email" class="form-control" id="email" value="{{ $user->email }}" disabled>
        </div>
        
        <!-- Input untuk password saat ini (diperlukan untuk mengubah password) -->
        <div class="form-group">
            <label for="current_password">Current Password (required to change password)</label>
            <input type="password" class="form-control" id="current_password" name="current_password">
        </div>
        
        <!-- Input untuk password baru -->
        <div class="form-group">
            <label for="new_password">New Password</label>
            <input type="password" class="form-control" id="new_password" name="new_password">
        </div>
        
        <!-- Input untuk konfirmasi password baru -->
        <div class="form-group">
            <label for="new_password_confirmation">Confirm New Password</label>
            <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation">
        </div>
        
        <!-- Tombol submit untuk mengupdate profil -->
        <button type="submit" class="btn btn-primary">Update Profile</button>
    </form>
</div>
@endsection