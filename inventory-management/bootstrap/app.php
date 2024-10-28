<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

// Penjelasan kode dalam bahasa Indonesia:

// File ini adalah titik masuk utama aplikasi Laravel.
// Kode di bawah ini mengkonfigurasi dan membuat instance aplikasi Laravel.

// Mengimpor kelas-kelas yang diperlukan dari Laravel

// Membuat dan mengkonfigurasi instance aplikasi Laravel
return Application::configure(basePath: dirname(__DIR__))
    // Mengatur rute aplikasi
    ->withRouting(
        web: __DIR__.'/../routes/web.php',      // Rute untuk aplikasi web
        commands: __DIR__.'/../routes/console.php', // Rute untuk perintah konsol
        health: '/up',                          // Endpoint untuk pemeriksaan kesehatan aplikasi
    )
    // Mengatur middleware aplikasi
    ->withMiddleware(function (Middleware $middleware) {
        // Di sini Anda dapat menambahkan konfigurasi middleware kustom
    })
    // Mengatur penanganan pengecualian
    ->withExceptions(function (Exceptions $exceptions) {
        // Di sini Anda dapat menambahkan konfigurasi penanganan pengecualian kustom
    })
    // Membuat dan mengembalikan instance aplikasi yang telah dikonfigurasi
    ->create();
