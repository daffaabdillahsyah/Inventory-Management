<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

// Kelas User yang mewarisi kelas Authenticatable dari Laravel
class User extends Authenticatable
{
    // Menggunakan trait Notifiable untuk menambahkan fungsionalitas notifikasi
    use Notifiable;

    // Daftar atribut yang dapat diisi secara massal (mass assignable)
    protected $fillable = [
        'username', 'email', 'password', 'role',
    ];

    // Daftar atribut yang harus disembunyikan saat mengubah model menjadi array atau JSON
    protected $hidden = [
        'password', 'remember_token',
    ];

    // Daftar atribut yang harus dikonversi ke tipe data tertentu
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}

// Penjelasan detail:
// 1. Namespace: App\Models - Menentukan lokasi kelas dalam struktur aplikasi
// 2. Use statements: Mengimpor kelas Authenticatable dan trait Notifiable
// 3. Class User: 
//    - Mewarisi Authenticatable untuk fungsionalitas otentikasi bawaan Laravel
//    - Menggunakan trait Notifiable untuk menambahkan kemampuan mengirim notifikasi
// 4. $fillable: 
//    - Mendefinisikan atribut yang dapat diisi secara massal
//    - Mencakup username, email, password, dan role
// 5. $hidden:
//    - Menyembunyikan atribut sensitif seperti password dan remember_token
//    - Berguna saat mengubah model menjadi array atau JSON
// 6. $casts:
//    - Mengonversi atribut 'email_verified_at' menjadi objek datetime
//    - Memudahkan manipulasi tanggal dan waktu

// Model ini digunakan untuk berinteraksi dengan tabel users dalam database,
// menyediakan fungsionalitas otentikasi, dan mengatur bagaimana data pengguna
// harus diperlakukan dalam aplikasi.
