<?php

namespace App\Notifications;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApproveUser extends Notification
{
    use Queueable;

    private $radmin_user;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $radmin_user, User $new_user)
    {
      $this->radmin_user = $radmin_user;
      $this->new_user = $new_user;
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
            ->subject('Dunkomatic Access Request Approval')
            ->greeting('Hello '. $this->new_user->name . ' !' )
            ->line('Region admin for region ' . $this->radmin_user->region . ' has approved your access request.')
            ->line('Make sure to verify your email and happy days with DUnkomatic !' );
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
