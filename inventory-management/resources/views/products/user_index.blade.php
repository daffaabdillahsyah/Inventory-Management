@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Order Products</h2>
    <form action="{{ route('orders.store') }}" method="POST" id="orderForm">
        @csrf
        <!-- Bagian untuk memilih produk -->
        <div id="productSelections">
            <div class="product-selection mb-3">
                <!-- Pilihan produk -->
                <div class="form-group">
                    <label for="product_id[]">Select Product</label>
                    <select name="product_id[]" class="form-control product-select" required>
                        <option value="">Choose a product</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" data-price="{{ $product->price }}" data-stock="{{ $product->stock }}">
                                {{ $product->name }} (Stock: {{ $product->stock }}) - Rp {{ number_format($product->price, 0, ',', '.') }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <!-- Input jumlah produk -->
                <div class="form-group">
                    <label for="quantity[]">Quantity</label>
                    <input type="number" name="quantity[]" class="form-control quantity-input" min="1" value="1" required>
                </div>
                <!-- Tampilan subtotal -->
                <div class="form-group">
                    <label>Subtotal</label>
                    <input type="text" class="form-control subtotal" readonly>
                </div>
            </div>
        </div>
        <!-- Tombol untuk menambah produk lain -->
        <button type="button" id="addProduct" class="btn btn-secondary mb-3">Add Another Product</button>
        <!-- Tampilan total harga -->
        <div class="form-group">
            <label>Total</label>
            <input type="text" id="total" class="form-control" readonly>
        </div>
        <!-- Tombol untuk melakukan pemesanan -->
        <button type="submit" class="btn btn-primary">Place Order</button>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const productSelections = document.getElementById('productSelections');
    const addProductBtn = document.getElementById('addProduct');
    const orderForm = document.getElementById('orderForm');

    // Fungsi untuk memperbarui subtotal
    function updateSubtotal(selection) {
        const select = selection.querySelector('.product-select');
        const quantity = selection.querySelector('.quantity-input').value;
        const subtotalInput = selection.querySelector('.subtotal');
        const price = select.options[select.selectedIndex].dataset.price;
        const subtotal = price * quantity;
        subtotalInput.value = 'Rp ' + subtotal.toLocaleString('id-ID');
    }

    // Fungsi untuk memperbarui total harga
    function updateTotal() {
        const subtotals = document.querySelectorAll('.subtotal');
        let total = 0;
        subtotals.forEach(subtotal => {
            total += parseInt(subtotal.value.replace(/[^0-9]/g, ''));
        });
        document.getElementById('total').value = 'Rp ' + total.toLocaleString('id-ID');
    }

    // Fungsi untuk menambah pilihan produk baru
    function addProductSelection() {
        const newSelection = productSelections.children[0].cloneNode(true);
        newSelection.querySelectorAll('input').forEach(input => input.value = '');
        productSelections.appendChild(newSelection);
        attachEventListeners(newSelection);
    }

    // Fungsi untuk menambahkan event listener pada pilihan produk
    function attachEventListeners(selection) {
        const select = selection.querySelector('.product-select');
        const quantityInput = selection.querySelector('.quantity-input');

        select.addEventListener('change', () => {
            updateSubtotal(selection);
            updateTotal();
        });

        quantityInput.addEventListener('input', () => {
            updateSubtotal(selection);
            updateTotal();
        });
    }

    // Menambahkan event listener untuk tombol "Add Another Product"
    addProductBtn.addEventListener('click', addProductSelection);

    // Menambahkan event listener untuk semua pilihan produk yang ada
    document.querySelectorAll('.product-selection').forEach(attachEventListeners);

    // Menangani pengiriman form
    orderForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        // Mengirim data form menggunakan fetch API
        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                window.location.href = data.redirect;
            } else {
                alert(data.message || 'An error occurred while placing the order.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while placing the order.');
        });
    });
});
</script>
@endsection
