<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    public $token;

    /**
     * Create a new notification instance.
     *
     * @param string $token
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param object $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Password Reset Request')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('We received a request to reset your password. If you made this request, click the button below to reset your password.')
            ->action('Reset Password', route('password.reset', ['token' => $this->token]))
            ->line('If you did not request a password reset, please ignore this email or contact support if you have any concerns.')
            ->line('Thank you for being a part of our application!')
            ->salutation('Best Regards, Your Application Team');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param object $notifiable
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            // Add any additional data here if needed
            'message' => 'Password reset notification sent.',
            'user' => $notifiable->name,
        ];
    }
}

