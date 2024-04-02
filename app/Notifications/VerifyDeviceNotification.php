<?php

namespace App\Notifications;

use App\Events\VerifyDeviceEvent;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Notifications\Dispatcher;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VerifyDeviceNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected User $user;

    protected string $device;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }

    public function handle(VerifyDeviceEvent $event)
    {
        $this->user = $event->user;
        $this->device = $event->device;

        app(Dispatcher::class)->sendNow($event->user, $this);
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
        return (new MailMessage)
            ->subject('Please verify your device')
            ->markdown('emails.verify-device', [
                'user' => $this->user,
                'profile' => $this->user->profile,
                'device' => $this->device,
            ]);
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
