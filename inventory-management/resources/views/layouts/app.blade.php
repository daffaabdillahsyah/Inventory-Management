<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Management</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <style>
        /* Gaya untuk mengatur tata letak halaman */
        body {
            padding-top: 56px; /* Memberikan ruang di atas untuk navbar */
        }
        .sidebar {
            position: fixed;
            top: 56px;
            bottom: 0;
            left: 0;
            z-index: 100;
            padding: 48px 0 0;
            box-shadow: inset -1px 0 0 rgba(0, 0, 0, .1);
        }
        .sidebar-sticky {
            position: relative;
            top: 0;
            height: calc(100vh - 48px);
            padding-top: .5rem;
            overflow-x: hidden;
            overflow-y: auto;
        }
        .content {
            margin-left: 200px;
            padding: 20px;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
        <a class="navbar-brand" href="{{ route('dashboard') }}">Inventory Management</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                @auth
                    <!-- Tampilkan nama pengguna dan tombol logout jika sudah login -->
                    <li class="nav-item">
                        <span class="nav-link">Welcome, {{ Auth::user()->username }}</span>
                    </li>
                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="POST" class="form-inline">
                            @csrf
                            <button type="submit" class="btn btn-link nav-link">Logout</button>
                        </form>
                    </li>
                @else
                    <!-- Tampilkan tombol login dan register jika belum login -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">Register</a>
                    </li>
                @endauth
            </ul>
        </div>
    </nav>

    @auth
        <!-- Tampilkan sidebar dan konten utama jika pengguna sudah login -->
        <div class="container-fluid">
            <div class="row">
                <!-- Sidebar -->
                <nav class="col-md-2 d-none d-md-block bg-light sidebar">
                    <div class="sidebar-sticky">
                        <ul class="nav flex-column">
                            <!-- Menu Dashboard untuk semua pengguna -->
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('dashboard') }}">
                                    <i class="fas fa-tachometer-alt"></i> Dashboard
                                </a>
                            </li>
                            @if(Auth::user()->role == 'admin')
                                <!-- Menu khusus untuk admin -->
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('products.index') }}">
                                        <i class="fas fa-box"></i> Manage Products
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('orders.index') }}">
                                        <i class="fas fa-shopping-cart"></i> Manage Orders
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('users.index') }}">
                                        <i class="fas fa-users"></i> Manage Users
                                    </a>
                                </li>
                            @else
                                <!-- Menu untuk pengguna biasa -->
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('orders.user') }}">
                                        <i class="fas fa-shopping-bag"></i> My Orders
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('profile.show') }}">
                                        <i class="fas fa-user"></i> Profile
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </nav>

                <!-- Konten utama -->
                <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4 content">
                    @yield('content')
                </main>
            </div>
        </div>
    @else
        <!-- Tampilkan hanya konten utama jika pengguna belum login -->
        <main role="main" class="container mt-4">
            @yield('content')
        </main>
    @endauth

    <!-- Bootstrap JS dan dependensinya -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    @yield('scripts')
</body>
</html>
