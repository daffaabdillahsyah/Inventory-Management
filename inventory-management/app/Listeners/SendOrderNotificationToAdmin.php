<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewOrderNotification;

// Kelas ini bertanggung jawab untuk mengirim notifikasi ke admin ketika sebuah pesanan baru dibuat
class SendOrderNotificationToAdmin
{
    /**
     * Create the event listener.
     */
    // Konstruktor kelas, saat ini tidak melakukan apa-apa karena tidak ada properti yang perlu diinisialisasi
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    // Metode ini dipanggil ketika event OrderCreated terjadi
    public function handle(OrderCreated $event)
    {
        // Mengambil semua pengguna dengan peran 'admin' dari database
        $admins = User::where('role', 'admin')->get();

        // Mengirim notifikasi ke semua admin yang ditemukan
        // Menggunakan facade Notification untuk mengirim NewOrderNotification
        // dengan data pesanan yang baru dibuat ($event->order)
        Notification::send($admins, new NewOrderNotification($event->order));
    }
}
