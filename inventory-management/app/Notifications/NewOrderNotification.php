<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Order;

// Kelas ini merupakan notifikasi untuk pesanan baru dalam sistem manajemen inventaris
class NewOrderNotification extends Notification
{
    // Trait Queueable digunakan untuk memungkinkan notifikasi ini diproses dalam antrian
    use Queueable;

    // Variabel protected untuk menyimpan instance pesanan
    protected $order;

    /**
     * Konstruktor untuk membuat instance notifikasi baru.
     * 
     * @param Order $order Instance pesanan yang akan dinotifikasikan
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Menentukan saluran pengiriman notifikasi.
     * 
     * @param mixed $notifiable Entitas yang akan menerima notifikasi
     * @return array<int, string> Array berisi saluran pengiriman
     */
    public function via($notifiable)
    {
        // Notifikasi akan disimpan di database
        return ['database'];
    }

    /**
     * Mengubah notifikasi menjadi representasi array.
     * 
     * @param mixed $notifiable Entitas yang akan menerima notifikasi
     * @return array<string, mixed> Array berisi data notifikasi
     */
    public function toArray($notifiable)
    {
        // Mengembalikan array dengan informasi pesanan yang relevan
        return [
            'order_id' => $this->order->id,           // ID pesanan
            'user_name' => $this->order->user->username,  // Nama pengguna yang membuat pesanan
            'total_amount' => $this->order->total_amount, // Jumlah total pesanan
        ];
    }
}
