@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Import Products</h2>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <form action="{{ route('products.import') }}" method="POST" enctype="multipart/form-data" id="importForm">
        @csrf
        <div class="form-group">
            <label for="file">Choose Excel File</label>
            <input type="file" class="form-control-file" id="file" name="file" accept=".xlsx, .xls">
            <div class="invalid-feedback" id="fileError"></div>
        </div>
        <button type="submit" class="btn btn-primary">Import Products</button>
    </form>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('importForm');
    const fileInput = document.getElementById('file');

    form.addEventListener('submit', function(event) {
        let isValid = true;

        // Validate file input
        if (fileInput.files.length === 0) {
            showError(fileInput, 'Please select a file to import');
            isValid = false;
        } else {
            const fileName = fileInput.files[0].name;
            const fileExtension = fileName.split('.').pop().toLowerCase();
            if (!['xlsx', 'xls'].includes(fileExtension)) {
                showError(fileInput, 'Please select a valid Excel file (.xlsx or .xls)');
                isValid = false;
            } else {
                clearError(fileInput);
            }
        }

        if (!isValid) {
            event.preventDefault();
        }
    });

    function showError(input, message) {
        input.classList.add('is-invalid');
        const errorDiv = input.nextElementSibling;
        errorDiv.textContent = message;
        errorDiv.style.display = 'block';
    }

    function clearError(input) {
        input.classList.remove('is-invalid');
        const errorDiv = input.nextElementSibling;
        errorDiv.textContent = '';
        errorDiv.style.display = 'none';
    }
});
</script>
@endsection

<!-- 
Penjelasan kode dalam bahasa Indonesia:

1. Struktur Blade:
   - Kode ini menggunakan layout 'app' sebagai template utama.
   - Konten utama didefinisikan dalam section 'content'.
   - Script JavaScript didefinisikan dalam section 'scripts'.

2. Konten Utama:
   - Menampilkan judul "Import Products".
   - Jika ada pesan error dalam session, akan ditampilkan dalam alert yang bisa ditutup.
   - Membuat form untuk mengimpor file Excel:
     - Form menggunakan metode POST dan dapat mengirim file.
     - Memiliki input file untuk memilih file Excel (.xlsx atau .xls).
     - Memiliki tombol submit untuk mengirim form.

3. Script JavaScript:
   - Script dijalankan setelah DOM selesai dimuat.
   - Mengambil referensi ke form dan input file.
   - Menambahkan event listener untuk submit form:
     - Melakukan validasi file yang dipilih:
       - Memastikan file telah dipilih.
       - Memeriksa ekstensi file (harus .xlsx atau .xls).
     - Jika validasi gagal, menampilkan pesan error dan mencegah pengiriman form.
   - Fungsi showError():
     - Menampilkan pesan error di bawah input file.
     - Menambahkan class 'is-invalid' ke input untuk styling error.
   - Fungsi clearError():
     - Menghapus pesan error dan menghilangkan styling error.

Kode ini membuat halaman untuk mengimpor produk melalui file Excel dengan validasi client-side untuk memastikan file yang dipilih valid sebelum dikirim ke server.
-->
