<?php

namespace App\Notifications;

use App\Models\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class CustomDbMessage extends Notification
{
    use Queueable;

    protected Message $message;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
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
            'greeting' => $this->message['greeting'],
            'subject' => $this->message['title'],
            'lines' => $this->message['body'],
            'salutation' => $this->message['salutation'],
            'sender' => $this->message->user->name,
            'tag' => $this->message->id,
        ];
    }
}
