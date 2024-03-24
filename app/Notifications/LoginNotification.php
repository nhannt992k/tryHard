<?php

namespace App\Notifications;

use Carbon\Carbon;
use Faker\Provider\UserAgent;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Jenssegers\Agent\Agent;


class LoginNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
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
     */
    public function toMail(object $notifiable): MailMessage
    {
        $time = Carbon::createFromFormat('Y-m-d H:i:s', '2024-03-19 07:24:30');
        $device = $notifiable->header('User-Agent',"Device unknown");
        return (new MailMessage)
                    ->line('Bạn đã đăng nhập trên thiết bị mới.'. $device)
                    ->line('vào lúc: '.$time)
                    ->line('Nếu không phải là bạn xin vui lòng đổi mật khẩu, bỏ qua nếu bạn là người đăng nhập.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
