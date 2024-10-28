<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// Kelas Product merupakan model Eloquent yang merepresentasikan tabel produk dalam database
class Product extends Model
{
    // $fillable adalah properti yang mendefinisikan atribut-atribut yang dapat diisi secara massal
    // Ini memungkinkan pengisian data produk seperti nama, deskripsi, harga, dan stok secara aman
    protected $fillable = ['name', 'description', 'price', 'stock'];

    // Metode media() mendefinisikan relasi "satu-ke-banyak" antara Product dan Media
    // Ini berarti satu produk dapat memiliki banyak media (misalnya gambar)
    public function media()
    {
        return $this->hasMany(Media::class);
    }

    // getPriceFormattedAttribute adalah sebuah accessor
    // Accessor ini memformat harga produk menjadi format mata uang Rupiah
    // Contoh: 1000000 akan diformat menjadi "Rp 1.000.000"
    public function getPriceFormattedAttribute()
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }
}
