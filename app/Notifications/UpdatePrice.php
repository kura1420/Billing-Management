<?php

namespace App\Notifications;

use App\Helpers\Formatter;
use App\Models\AppProfile;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UpdatePrice extends Notification
{
    use Queueable;

    private $prices;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($prices)
    {
        //
        $this->prices = (object) $prices;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $appProfile = AppProfile::first();

        return (new MailMessage)
                    ->subject($appProfile->name . ' - Update Price')
                    ->line('Kepada Yth. ')
                    ->line('Bersama dengan email ini kami informasi bahwa pada Bulan ' . date('F Y') . ' telah diberlakukan harga baru terhadap layanan Anda.')
                    ->line('Dengan detail, sebagai berikut: ')
                    ->line('Harga Satuan: ' . Formatter::rupiah($this->prices->price_sub))
                    ->line('Harga Pajak: ' . Formatter::rupiah($this->prices->price_ppn))
                    ->line('Harga Total: ' . Formatter::rupiah($this->prices->price_total))
                    ->line('Terbilang: ' . Formatter::rupiahSpeakOnBahasa($this->prices->price_total))
                    ->line('Demikian informasi yang dapat kami sampaikan, terimakasih.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
