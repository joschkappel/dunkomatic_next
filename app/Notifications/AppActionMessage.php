<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppActionMessage extends Notification
{
    use Queueable;

    protected string $msg;
    protected string $subject;

    /**
     * Create a new notification instance.
     *
     * @param string $subject
     * @param string $body
     * @return void
     */
    public function __construct(string $subject, string $body)
    {
        $this->msg = $body;
        $this->subject = $subject;
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
              'greeting' => '',
              'subject' => $this->subject,
              'lines' => $this->msg,
              'salutation' => config('app.name'),
        ];
    }
}
