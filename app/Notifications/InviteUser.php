<?php

namespace App\Notifications;

use App\Models\User;
use App\Models\Club;
use App\Enums\Role;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Crypt;

class InviteUser extends Notification
{
    use Queueable;

    private $sender;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $sender)
    {
        $this->sender = $sender;
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
        $sender = $this->sender->member->name . ' (' . Role::fromValue($this->sender->member->memberships->first()->role_id)->description . ' von ' . $this->sender->member->region->first()->name . ') ';
        return (new MailMessage)
            ->level('info')
            ->subject( __('notifications.inviteuser.subject'))
            ->greeting( __('notifications.user.greeting', ['username'=>$notifiable->name]))
            ->line( __('notifications.inviteuser.line1', ['sendername'=>$sender]))
            ->line( __('notifications.inviteuser.line2'))
            ->action( __('notifications.inviteuser.action'), route('register.invited', ['language'=>app()->getLocale(), 'member' => $notifiable, 'inviting_user'=>$this->sender, 'invited_by'=>Crypt::encryptString($notifiable->email1)]));
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
