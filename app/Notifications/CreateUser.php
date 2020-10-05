<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;

class CreateUser extends Notification
{
    use Queueable;

    public function via($notifiable)
    {
        return ['slack', 'mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('User is Created Succesfully!')
                    ->line('Thank you for using our application!');
    }

    public function toSlack($notifiable)
    {
        return (new SlackMessage)
                ->success()
                ->content('Post Created Successfully!');
    }
}
