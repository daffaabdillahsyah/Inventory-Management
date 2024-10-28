<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// Kelas Media merupakan model Eloquent yang merepresentasikan tabel media dalam database
class Media extends Model
{
    // $fillable adalah properti yang mendefinisikan atribut-atribut yang dapat diisi secara massal
    // Dalam hal ini, atribut yang dapat diisi adalah 'product_id', 'file_name', 'file_type', dan 'file_path'
    protected $fillable = ['product_id', 'file_name', 'file_type', 'file_path'];

    // Metode product() mendefinisikan relasi "belongs to" antara Media dan Product
    // Ini berarti setiap media terkait dengan satu produk
    public function product()
    {
        // Metode ini mengembalikan relasi belongsTo ke model Product
        // Ini mengindikasikan bahwa setiap instance Media memiliki satu Product terkait
        return $this->belongsTo(Product::class);
    }
}

// Penjelasan tambahan:
// - Kelas ini menggunakan namespace App\Models, yang merupakan konvensi Laravel untuk menyimpan model-model
// - Kelas ini meng-extend Model dari Eloquent, yang memberikan berbagai fitur ORM (Object-Relational Mapping)
// - Dengan menggunakan Eloquent, kita dapat dengan mudah berinteraksi dengan database tanpa menulis query SQL secara langsung
// - Model ini diasumsikan terkait dengan tabel 'media' di database (Laravel akan menggunakan nama tabel jamak secara otomatis)
// - Relasi yang didefinisikan memungkinkan kita untuk mengakses produk terkait dari instance Media dengan mudah, misalnya: $media->product
