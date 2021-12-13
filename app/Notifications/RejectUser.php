<?php

namespace App\Notifications;

use App\Models\User;
use App\Models\Region;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RejectUser extends Notification
{
    use Queueable;

    private $radmin_user;
    private $new_user;
    private $region;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $radmin_user, User $new_user, Region $region)
    {
      $this->radmin_user = $radmin_user;
      $this->region = $region;
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
            ->subject( __('notifications.rejectuser.subject') )
            ->greeting( __('notifications.user.greeting', ['username' => $this->new_user->name]) )
            ->line( __('notifications.rejectuser.line1',
                ['region' => $this->region->name.' ('.$this->region->code.') ',
                 'reason' => $this->new_user->reason_reject]) )
            ->line( __('notifications.rejectuser.line2', ['email' => $this->radmin_user->email]) );
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
