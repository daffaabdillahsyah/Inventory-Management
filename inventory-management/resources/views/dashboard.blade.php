@extends('layouts.app')

@section('content')
<!-- Bootstrap CSS -->
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<!-- Font Awesome untuk ikon -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
<!-- Gaya kustom -->
<style>
    /* Efek transisi untuk kartu dashboard */
    .dashboard-card {
        transition: all 0.3s;
    }
    /* Efek hover untuk kartu dashboard */
    .dashboard-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    /* Margin kanan untuk tombol dashboard */
    .btn-dashboard {
        margin-right: 10px;
    }
</style>

<div class="container-fluid">
    <!-- Judul halaman dashboard -->
    <h2 class="mb-4">User Dashboard</h2>
    <!-- Pesan selamat datang untuk pengguna -->
    <div class="alert alert-info">
        <p class="mb-0">Welcome, {{ $user->username }}!</p>
    </div>
    
    <!-- Baris untuk menampilkan statistik pengguna -->
    <div class="row mb-4">
        <!-- Kolom untuk total pesanan -->
        <div class="col-md-6">
            <div class="card dashboard-card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Total Orders</h5>
                    <p class="card-text display-4">{{ $userOrders }}</p>
                </div>
            </div>
        </div>
        <!-- Kolom untuk pesanan terakhir -->
        <div class="col-md-6">
            <div class="card dashboard-card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Last Order</h5>
                    <p class="card-text">
                        @if($lastOrder)
                            Order #{{ $lastOrder->id }} - {{ $lastOrder->created_at->format('d/m/Y') }}
                        @else
                            No orders yet
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tombol-tombol aksi -->
    <div class="mt-4">
        <!-- Tombol untuk membuat pesanan baru -->
        <button type="button" class="btn btn-primary btn-dashboard" data-toggle="modal" data-target="#createOrderModal">
            <i class="fas fa-plus-circle"></i> Create Order
        </button>
        <!-- Tombol untuk melihat pesanan pengguna -->
        <a href="{{ route('orders.user') }}" class="btn btn-info btn-dashboard">
            <i class="fas fa-user-clock"></i> My Orders
        </a>
        <!-- Tombol untuk melihat produk -->
        <a href="{{ route('products.user_view') }}" class="btn btn-secondary">
            <i class="fas fa-box"></i> View Products
        </a>
    </div>
</div>

<!-- Modal untuk membuat pesanan -->
<div class="modal fade" id="createOrderModal" tabindex="-1" role="dialog" aria-labelledby="createOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createOrderModalLabel">Create Order</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Form untuk membuat pesanan -->
                <form id="orderForm">
                    @csrf
                    <div id="orderItems">
                        <!-- Template item pesanan -->
                        <div class="order-item">
                            <!-- Dropdown untuk memilih produk -->
                            <select name="product_id[]" class="form-control product-select" required>
                                <option value="">Select a product</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" data-price="{{ $product->price }}">
                                        {{ $product->name }} - Rp {{ number_format($product->price, 0, ',', '.') }} (Stock: {{ $product->stock }})
                                    </option>
                                @endforeach
                            </select>
                            <!-- Input untuk jumlah produk -->
                            <input type="number" name="quantity[]" class="form-control quantity-input" min="1" value="1" required>
                        </div>
                    </div>
                    <!-- Tombol untuk menambah item pesanan -->
                    <button type="button" class="btn btn-secondary mt-3" id="addMoreItems">
                        <i class="fas fa-plus"></i> Add More Items
                    </button>
                    <!-- Menampilkan total harga pesanan -->
                    <p class="mt-3 h5">Grand Total: <span id="grandTotal" class="font-weight-bold">Rp 0</span></p>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="submitOrder">Place Order</button>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS dan dependensinya -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<!-- Konfigurasi AJAX untuk CSRF token -->
<script>
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
</script>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    let itemCount = 1;

    // Fungsi untuk memperbarui total harga
    function updateTotals() {
        let grandTotal = 0;
        $('.order-item').each(function() {
            const price = parseFloat($(this).find('.product-select option:selected').data('price')) || 0;
            const quantity = parseInt($(this).find('.quantity-input').val()) || 0;
            const total = price * quantity;
            $(this).find('.item-total').text(`Total: Rp ${total.toLocaleString('id-ID')}`);
            grandTotal += total;
        });
        $('#grandTotal').text(`Rp ${grandTotal.toLocaleString('id-ID')}`);
    }

    // Event listener untuk perubahan produk dan jumlah
    $(document).on('change', '.product-select, .quantity-input', updateTotals);
    $(document).on('input', '.quantity-input', updateTotals);

    // Fungsi untuk menambah item pesanan baru
    $('#addMoreItems').click(function() {
        itemCount++;
        const newItem = $('.order-item').first().clone();
        newItem.find('select, input').val('');
        newItem.find('.item-total').text('Total: Rp 0');
        $('#orderItems').append(newItem);
    });

    // Fungsi untuk mengirim pesanan
    $('#submitOrder').click(function() {
        var formData = $('#orderForm').serialize();
        $.ajax({
            url: '{{ route("orders.store") }}',
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    alert('Order placed successfully!');
                    $('#createOrderModal').modal('hide');
                    location.reload();
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function(xhr) {
                var errors = xhr.responseJSON.errors;
                var errorMessage = '';
                for (var key in errors) {
                    errorMessage += errors[key][0] + '\n';
                }
                alert('Error: ' + errorMessage);
            }
        });
    });

    // Inisialisasi total harga saat halaman dimuat
    updateTotals();
});
</script>
@endsection
