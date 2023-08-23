<?php

namespace App\Notifications;

use App\Events\NewUserEvent;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Notifications\Dispatcher;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewUserNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $user;

    protected $password;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
    
    public function handle(NewUserEvent $event)
    {
        $this->password = $event->password;
        $this->user = $event->user;
        
        app(Dispatcher::class)->sendNow($event->user, $this);
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
            ->subject('Your account has been created')
            ->greeting("Hi {$this->user->profile->first_name},")
            ->line('Your pos account has been created.')
            ->line('Login to your account using the details below.')
            ->line("Email: {$this->user->email}")
            ->line("Password: {$this->password}")
            ->action('Login', url('/'));
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
