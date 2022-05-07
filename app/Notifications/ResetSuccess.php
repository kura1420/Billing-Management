<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetSuccess extends Notification
{
    use Queueable;

    protected $newPassword;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($newPassword)
    {
        //
        $this->newPassword = $newPassword;
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
        return (new MailMessage)
                    ->subject('Reset Password Success')
                    ->greeting('Dear ')
                    ->line('Permintaan reset password anda telah kami proses, berikut detail akses terbaru dari akun anda:')
                    ->line('Email: ')
                    ->line('Password: ' . $this->newPassword)
                    ->action('Login Ke Aplikasi', url('/'))
                    ->line('Terimakasih');
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
