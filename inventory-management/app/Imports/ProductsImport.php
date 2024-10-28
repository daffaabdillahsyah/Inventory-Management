<?php

namespace App\Imports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

// Kelas ini digunakan untuk mengimpor data produk dari file Excel
// Kelas ini mengimplementasikan ToModel untuk mengonversi baris Excel menjadi model
// dan WithHeadingRow untuk menggunakan baris pertama sebagai header
class ProductsImport implements ToModel, WithHeadingRow
{
    // Metode ini dipanggil untuk setiap baris dalam file Excel (kecuali baris header)
    // Parameter $row adalah array yang berisi data dari satu baris Excel
    public function model(array $row)
    {
        // Membuat dan mengembalikan instance baru dari model Product
        // dengan data yang diambil dari baris Excel
        return new Product([
            // Mengisi kolom 'name' dengan nilai dari kolom 'name' atau 'Name' di Excel
            // Jika keduanya tidak ada, menggunakan string kosong sebagai nilai default
            'name'        => $row['name'] ?? $row['Name'] ?? '',
            
            // Mengisi kolom 'description' dengan nilai dari kolom 'description' atau 'Description' di Excel
            // Jika keduanya tidak ada, menggunakan string kosong sebagai nilai default
            'description' => $row['description'] ?? $row['Description'] ?? '',
            
            // Mengisi kolom 'price' dengan nilai dari kolom 'price' atau 'Price' di Excel
            // Jika keduanya tidak ada, menggunakan 0 sebagai nilai default
            'price'       => $row['price'] ?? $row['Price'] ?? 0,
            
            // Mengisi kolom 'stock' dengan nilai dari kolom 'stock' atau 'Stock' di Excel
            // Jika keduanya tidak ada, menggunakan 0 sebagai nilai default
            'stock'       => $row['stock'] ?? $row['Stock'] ?? 0,
        ]);
    }
}
