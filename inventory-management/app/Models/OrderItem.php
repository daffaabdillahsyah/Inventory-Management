<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Kelas OrderItem merepresentasikan item dalam sebuah pesanan
// Kelas ini mewarisi dari kelas Model Eloquent Laravel
class OrderItem extends Model
{
    // Trait HasFactory digunakan untuk memungkinkan pembuatan instance model menggunakan factory
    use HasFactory;

    // $fillable adalah array yang berisi nama kolom-kolom yang dapat diisi secara massal
    // Ini memungkinkan pengisian data secara aman menggunakan metode create() atau update()
    protected $fillable = ['order_id', 'product_id', 'quantity', 'price'];

    // Metode order() mendefinisikan relasi "belongs to" dengan model Order
    // Ini berarti setiap OrderItem terkait dengan satu Order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Metode product() mendefinisikan relasi "belongs to" dengan model Product
    // Ini berarti setiap OrderItem terkait dengan satu Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
