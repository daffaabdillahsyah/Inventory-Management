@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Judul halaman untuk mengedit pengguna -->
    <h2>Edit User</h2>
    <!-- Form untuk mengupdate data pengguna -->
    <form action="{{ route('users.update', $user->id) }}" method="POST" id="editUserForm">
        <!-- Token CSRF untuk keamanan form -->
        @csrf
        <!-- Metode HTTP yang digunakan adalah PUT untuk update -->
        @method('PUT')
        <!-- Input untuk username -->
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" class="form-control" id="username" name="username" value="{{ $user->username }}" required>
            <div class="invalid-feedback" id="usernameError"></div>
        </div>
        <!-- Input untuk email -->
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" required>
            <div class="invalid-feedback" id="emailError"></div>
        </div>
        <!-- Dropdown untuk memilih peran pengguna -->
        <div class="form-group">
            <label for="role">Role</label>
            <select class="form-control" id="role" name="role">
                <!-- Opsi untuk peran 'user' -->
                <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>User</option>
                <!-- Opsi untuk peran 'admin' -->
                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
        </div>
        <!-- Tombol submit untuk mengupdate data pengguna -->
        <button type="submit" class="btn btn-primary">Update User</button>
    </form>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Menunggu DOM selesai dimuat sebelum menjalankan script

    // Mendapatkan referensi ke elemen-elemen form
    const form = document.getElementById('editUserForm');
    const usernameInput = document.getElementById('username');
    const emailInput = document.getElementById('email');

    // Menambahkan event listener untuk submit form
    form.addEventListener('submit', function(event) {
        let isValid = true;

        // Validasi username
        if (usernameInput.value.trim() === '') {
            // Jika username kosong, tampilkan pesan error
            showError(usernameInput, 'Username is required');
            isValid = false;
        } else {
            // Jika username valid, hapus pesan error
            clearError(usernameInput);
        }

        // Validasi email
        if (emailInput.value.trim() === '') {
            // Jika email kosong, tampilkan pesan error
            showError(emailInput, 'Email is required');
            isValid = false;
        } else if (!isValidEmail(emailInput.value)) {
            // Jika format email tidak valid, tampilkan pesan error
            showError(emailInput, 'Please enter a valid email address');
            isValid = false;
        } else {
            // Jika email valid, hapus pesan error
            clearError(emailInput);
        }

        // Jika ada error, cegah form dikirim
        if (!isValid) {
            event.preventDefault();
        }
    });

    // Fungsi untuk menampilkan pesan error
    function showError(input, message) {
        input.classList.add('is-invalid');
        const errorDiv = input.nextElementSibling;
        errorDiv.textContent = message;
        errorDiv.style.display = 'block';
    }

    // Fungsi untuk menghapus pesan error
    function clearError(input) {
        input.classList.remove('is-invalid');
        const errorDiv = input.nextElementSibling;
        errorDiv.textContent = '';
        errorDiv.style.display = 'none';
    }

    // Fungsi untuk memvalidasi format email menggunakan regex
    function isValidEmail(email) {
        const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(String(email).toLowerCase());
    }
});
</script>
@endsection
