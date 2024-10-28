@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Create New Order</h2>
    <form action="{{ route('orders.store') }}" method="POST" id="orderForm">
        @csrf
        <div id="orderItems">
            <div class="order-item mb-3">
                <div class="row">
                    <!-- Bagian untuk memilih produk -->
                    <div class="col-md-3">
                        <label>Product</label>
                        <select name="product_id[]" class="form-control product-select" required>
                            <option value="">Select a product</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" 
                                    data-price="{{ $product->price }}" 
                                    data-stock="{{ $product->stock }}"
                                    data-image="{{ $product->media->first()->file_path ?? '' }}">
                                    {{ $product->name }} (Stock: {{ $product->stock }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Bagian untuk menampilkan harga per item -->
                    <div class="col-md-2">
                        <label>Price per Item</label>
                        <input type="text" class="form-control price-per-item" readonly>
                    </div>
                    <!-- Bagian untuk memasukkan kuantitas -->
                    <div class="col-md-2">
                        <label>Quantity</label>
                        <input type="number" name="quantity[]" class="form-control quantity-input" min="1" value="1" required>
                    </div>
                    <!-- Bagian untuk menampilkan total harga per item -->
                    <div class="col-md-2">
                        <label>Total</label>
                        <input type="text" class="form-control item-total" readonly>
                    </div>
                    <!-- Bagian untuk menampilkan gambar produk -->
                    <div class="col-md-3">
                        <label>Image</label>
                        <img src="" alt="Product Image" class="product-image img-fluid" style="max-height: 100px; display: none;">
                    </div>
                </div>
            </div>
        </div>
        <!-- Tombol untuk menambah item baru -->
        <button type="button" id="addItem" class="btn btn-secondary mb-3">Add Another Item</button>
        <!-- Bagian untuk menampilkan grand total -->
        <div class="mb-3">
            <strong>Grand Total: <span id="grandTotal">Rp 0</span></strong>
        </div>
        <!-- Tombol untuk membuat pesanan -->
        <button type="submit" class="btn btn-primary">Create Order</button>
    </form>
</div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const orderItems = document.getElementById('orderItems');
    const addItemBtn = document.getElementById('addItem');
    const orderForm = document.getElementById('orderForm');

    // Fungsi untuk memperbarui harga dan total
    function updatePriceAndTotal(item) {
        const select = item.querySelector('.product-select');
        const pricePerItemInput = item.querySelector('.price-per-item');
        const quantityInput = item.querySelector('.quantity-input');
        const totalInput = item.querySelector('.item-total');
        const imageElement = item.querySelector('.product-image');

        const selectedOption = select.options[select.selectedIndex];
        const price = parseFloat(selectedOption.dataset.price) || 0;
        const quantity = parseInt(quantityInput.value) || 0;
        const total = price * quantity;

        // Memperbarui tampilan harga dan total
        pricePerItemInput.value = 'Rp ' + price.toLocaleString('id-ID');
        totalInput.value = 'Rp ' + total.toLocaleString('id-ID');

        // Memperbarui gambar produk
        const imagePath = selectedOption.dataset.image;
        if (imagePath) {
            imageElement.src = '/storage/' + imagePath;
            imageElement.style.display = 'block';
        } else {
            imageElement.style.display = 'none';
        }
    }

    // Fungsi untuk memperbarui grand total
    function updateGrandTotal() {
        let grandTotal = 0;
        document.querySelectorAll('.item-total').forEach(item => {
            grandTotal += parseFloat(item.value.replace('Rp ', '').replace(/\./g, '')) || 0;
        });
        document.getElementById('grandTotal').textContent = 'Rp ' + grandTotal.toLocaleString('id-ID');
    }

    // Fungsi untuk menambah item baru
    function addNewItem() {
        const newItem = orderItems.querySelector('.order-item').cloneNode(true);
        newItem.querySelector('.product-select').selectedIndex = 0;
        newItem.querySelector('.quantity-input').value = 1;
        newItem.querySelector('.price-per-item').value = '';
        newItem.querySelector('.item-total').value = '';
        newItem.querySelector('.product-image').src = '';
        newItem.querySelector('.product-image').style.display = 'none';
        
        // Menambahkan tombol remove
        const removeBtn = document.createElement('button');
        removeBtn.type = 'button';
        removeBtn.className = 'btn btn-danger btn-sm remove-item mt-2';
        removeBtn.textContent = 'Remove';
        removeBtn.onclick = function() {
            newItem.remove();
            updateGrandTotal();
        };
        
        newItem.querySelector('.row').appendChild(removeBtn);
        orderItems.appendChild(newItem);
        setupItemListeners(newItem);
        updatePriceAndTotal(newItem);
        updateGrandTotal();
    }

    // Fungsi untuk mengatur event listener pada item
    function setupItemListeners(item) {
        const select = item.querySelector('.product-select');
        const quantityInput = item.querySelector('.quantity-input');

        select.addEventListener('change', () => {
            updatePriceAndTotal(item);
            updateGrandTotal();
        });

        quantityInput.addEventListener('input', () => {
            updatePriceAndTotal(item);
            updateGrandTotal();
        });
    }

    // Menambahkan event listener untuk tombol "Add Another Item"
    addItemBtn.addEventListener('click', addNewItem);

    // Mengatur event listener untuk item awal
    setupItemListeners(orderItems.querySelector('.order-item'));

    // Menangani pengiriman form
    orderForm.addEventListener('submit', function(e) {
        e.preventDefault();
        let formData = new FormData(this);
        
        // Log data form sebelum mengirim
        for (let pair of formData.entries()) {
            console.log(pair[0] + ': ' + pair[1]);
        }

        // Mengirim data form menggunakan fetch API
        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Order created successfully!');
                window.location.href = data.redirect;
            } else {
                alert('Error: ' + (data.message || 'An error occurred while creating the order.'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while creating the order.');
        });
    });

    // Inisialisasi awal
    updatePriceAndTotal(orderItems.querySelector('.order-item'));
    updateGrandTotal();
});
</script>
@endsection
