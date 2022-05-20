<?php

namespace App\Notifications;

use App\Models\AppProfile;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class Unsuspend extends Notification
{
    use Queueable;

    private $params;
    private $customBody;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($params, $customBody = NULL)
    {
        //
        $this->params = $params;
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
                ->subject($appProfile->name . ' - Unsuspend')
                ->view('billing.invoice.email.notif', [
                    'content' => $this->customBody,
                ]);
        } else {
            return (new MailMessage)
                ->subject($appProfile->name . ' - Unsuspend')
                ->greeting('Kepada Yth. ' . $this->params[8])
                ->line('Layanan anda telah di Unsuspend, harap segera melakukan pembayaran untuk tagihan Bulan '. date('F Y', strtotime($this->params[1])) . ' sebesar ' . $this->params[5])
                ->line('Bila layanan tersebut belum dilakukan pembayaran maka dalam waktu 24Jam, layanan anda akan kami Suspend kembali.');
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
