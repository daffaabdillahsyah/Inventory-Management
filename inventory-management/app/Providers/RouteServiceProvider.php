<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AdminMiddleware;

// Kelas RouteServiceProvider merupakan kelas yang bertanggung jawab untuk mengatur rute dalam aplikasi Laravel
class RouteServiceProvider extends ServiceProvider
{
    /**
     * Konstanta HOME mendefinisikan path untuk rute "home" dalam aplikasi.
     * 
     * Ini digunakan oleh sistem autentikasi Laravel untuk mengarahkan pengguna setelah login.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Metode boot() dijalankan saat aplikasi di-boot.
     * Di sini kita mendefinisikan binding model rute, filter pola, dan konfigurasi lainnya.
     *
     * @return void
     */
    public function boot()
    {
        // Memanggil metode untuk mengkonfigurasi rate limiting
        $this->configureRateLimiting();

        // Mendefinisikan rute-rute aplikasi
        $this->routes(function () {
            // Mengelompokkan rute-rute web dengan middleware 'web'
            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            // Menambahkan alias untuk middleware admin
            // Ini memungkinkan kita menggunakan 'admin.access' sebagai nama middleware dalam definisi rute
            $this->app['router']->aliasMiddleware('admin.access', AdminMiddleware::class);
        });
    }

    /**
     * Metode ini digunakan untuk mengkonfigurasi rate limiting untuk aplikasi.
     * Rate limiting adalah teknik untuk membatasi jumlah request yang dapat dilakukan dalam periode waktu tertentu.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        // Implementasi rate limiting belum didefinisikan
    }
}
