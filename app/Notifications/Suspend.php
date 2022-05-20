<?php

namespace App\Notifications;

use App\Models\AppProfile;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Storage;

class Suspend extends Notification
{
    use Queueable;

    private $params;
    private $filepath;
    private $customBody;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($params, $filepath, $customBody = NULL)
    {
        //
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

        if ($this->customBody) {
            return (new MailMessage)
                ->subject($appProfile->name . ' - Suspend')
                ->view('billing.invoice.email.notif', [
                    'content' => $this->customBody,
                ])
                ->attach(Storage::path($this->filepath));
        } else {
            return (new MailMessage)
                ->subject($appProfile->name . ' - Suspend')
                ->greeting('Kepada Yth. ' . $this->params[8])
                ->line('Berikut kami informasikan kembali tagihan ' . $appProfile->name . ' Bulan ' . date('F Y') . ' sebesar ' . $this->params[5])
                ->line('Silahkan melakukan pembayaran tanggal ' . $this->params[1]. ', bilamana belum melakukan pembayaran maka dengan berat hati kami akan mensuspend jaringan internet Anda.')
                ->line('Jika masih belum dilakukan juga dan melewati tanggal ' . $this->params[2] . ', melalui informasi ini kami akan melakukan pemutusan secara keseluruhan.')
                ->attach(Storage::path($this->filepath));
        }
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
