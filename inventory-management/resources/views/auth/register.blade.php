@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Card untuk form registrasi -->
            <div class="card shadow-lg mb-4">
                <!-- Header card -->
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">Register</h4>
                </div>
                <!-- Isi card -->
                <div class="card-body">
                    <!-- Form registrasi -->
                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        <!-- Input username -->
                        <div class="form-group">
                            <label for="username">
                                <i class="fas fa-user"></i> Username
                            </label>
                            <input type="text" id="username" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required autocomplete="username" autofocus>
                            <!-- Pesan error untuk username -->
                            @error('username')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <!-- Input email -->
                        <div class="form-group">
                            <label for="email">
                                <i class="fas fa-envelope"></i> Email
                            </label>
                            <input type="email" id="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
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
                            <input type="password" id="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                            <!-- Pesan error untuk password -->
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <!-- Input konfirmasi password -->
                        <div class="form-group">
                            <label for="password_confirmation">
                                <i class="fas fa-lock"></i> Confirm Password
                            </label>
                            <input type="password" id="password_confirmation" class="form-control" name="password_confirmation" required autocomplete="new-password">
                        </div>
                        <!-- Checkbox untuk registrasi sebagai admin -->
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="is_admin" id="is_admin" value="1">
                                <label class="custom-control-label" for="is_admin">Register as Admin</label>
                            </div>
                        </div>
                        <!-- Tombol submit -->
                        <button type="submit" class="btn btn-success btn-block">
                            <i class="fas fa-user-plus"></i> Register
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Card untuk link ke halaman login -->
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <p class="mb-0">Already have an account? <a href="{{ route('login') }}" class="btn btn-outline-success btn-sm ml-2">Login here</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
