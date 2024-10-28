<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Kelas Order merepresentasikan model untuk tabel orders dalam database
class Order extends Model
{
    // Trait HasFactory digunakan untuk membuat factory untuk model ini,
    // yang berguna untuk testing dan seeding database
    use HasFactory;

    // $fillable adalah array yang berisi daftar kolom yang dapat diisi secara massal
    // Ini meningkatkan keamanan dengan mencegah pengisian kolom yang tidak diinginkan
    protected $fillable = [
        'user_id',      // ID pengguna yang membuat pesanan
        'total_amount', // Jumlah total pesanan
        'status',       // Status pesanan (misalnya: pending, completed, cancelled)
    ];

    // Metode ini mendefinisikan relasi "belongs to" dengan model User
    // Ini berarti setiap pesanan dimiliki oleh satu pengguna
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Metode ini mendefinisikan relasi "has many" dengan model OrderItem
    // Ini berarti satu pesanan dapat memiliki banyak item pesanan
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
