<?php

namespace App\Notifications;

use App\Models\AppProfile;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class Terminate extends Notification
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
                ->subject($appProfile->name . ' - Terminated')
                ->view('billing.invoice.email.notif', [
                    'content' => $this->customBody,
                ]);
        } else {
            return (new MailMessage)
                ->subject($appProfile->name . ' - Terminated')
                ->greeting('Kepada Yth. ' . $this->params[8])
                ->line('Bersama ini kami informasikan kembali bahwa untuk layanan Anda telah kami lakukan pemutusan layanan.')
                ->line('Sehingga anda tidak dapat menggunakan layanan ini kembali, untuk informasi lebih lanjut silahkan hubungi Call Center.');
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
