<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

// Kelas ProductsExport digunakan untuk mengekspor data produk ke dalam format Excel
// Kelas ini mengimplementasikan FromCollection untuk mengambil data dari koleksi
// dan WithHeadings untuk menambahkan judul kolom pada file Excel yang dihasilkan
class ProductsExport implements FromCollection, WithHeadings
{
    // Metode collection() digunakan untuk mengambil data produk yang akan diekspor
    // Metode ini mengembalikan koleksi produk dengan kolom yang dipilih: name, description, price, dan stock
    public function collection()
    {
        return Product::select('name', 'description', 'price', 'stock')->get();
    }

    // Metode headings() digunakan untuk menentukan judul kolom pada file Excel yang dihasilkan
    // Metode ini mengembalikan array yang berisi nama-nama kolom dalam bahasa Inggris
    public function headings(): array
    {
        return [
            'Name',
            'Description',
            'Price',
            'Stock',
        ];
    }
}
