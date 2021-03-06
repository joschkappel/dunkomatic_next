<?php

namespace App\Notifications;

use App\Models\User;
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
            ->subject( __('notifications.approveuser.subject') )
            ->greeting( __('notifications.user.greeting', ['username' => $this->new_user->name]) )
            ->line( __('notifications.approveuser.line1', ['region'=>$this->radmin_user->region->name]) )
            ->line( __('notifications.approveuser.line2' ));
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
