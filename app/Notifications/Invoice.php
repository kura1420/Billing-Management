<?php

namespace App\Notifications;

use App\Models\AppProfile;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Storage;

class Invoice extends Notification
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
        $this->params = $params;
        $this->filepath = $filepath;
        $this->customBody = $customBody;
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
                ->view('billing.invoice.notif', [
                    'content' => $this->customBody,
                ])
                ->attach(Storage::path($this->filepath));
        } else {
            return (new MailMessage)
                ->greeting('Kepada Yth. ' . $this->params[8])
                ->line('Berikut kami informasikan tagihan ' . $appProfile->name . ' Bulan ' . date('F Y') . ' sebesar ' . $this->params[5])
                ->line('Silahkan melakukan pembayaran sebelum tanggal ' . $this->params[2]. '.')
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
