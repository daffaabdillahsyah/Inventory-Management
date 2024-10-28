@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Kartu untuk form login -->
            <div class="card shadow-lg mb-4">
                <!-- Header kartu -->
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Login</h4>
                </div>
                <!-- Isi kartu -->
                <div class="card-body">
                    <!-- Form login -->
                    <form method="POST" action="{{ url('/') }}">
                        @csrf
                        <!-- Input email -->
                        <div class="form-group">
                            <label for="email">
                                <i class="fas fa-envelope"></i> Email
                            </label>
                            <input type="email" id="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                            <!-- Pesan error untuk email -->
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <!-- Input password -->
                        <div class="form-group">
                            <label for="password">
                                <i class="fas fa-lock"></i> Password
                            </label>
                            <input type="password" id="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                            <!-- Pesan error untuk password -->
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <!-- Tombol submit -->
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-sign-in-alt"></i> Login
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Pesan sukses jika ada -->
            @if (session('success'))
                <div class="alert alert-success mb-4">
                    {{ session('success') }}
                </div>
            @endif
            
            <!-- Kartu untuk link registrasi -->
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <p class="mb-0">Don't have an account? <a href="{{ route('register') }}" class="btn btn-outline-primary btn-sm ml-2">Register here</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
