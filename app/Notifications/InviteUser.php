<?php

namespace App\Notifications;

use App\Models\User;
use App\Enums\Role;
use App\Models\Invitation;
use App\Models\Region;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Crypt;

class InviteUser extends Notification
{
    use Queueable;

    private User $sender;
    private Region $invite_to_region;
    private Invitation $invitation;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Invitation $invitation)
    {
        $this->sender = $invitation->user;
        $this->invite_to_region = $invitation->region;
        $this->invitation = $invitation;
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
        if ( $this->sender->member->memberships->count() >0 ){
            $sender = $this->sender->member->name . ' (' . Role::fromValue($this->sender->member->memberships->first()->role_id)->description . ' von ' . $this->sender->member->region->first()->name . ') ';
        } else {
            $sender = $this->sender->name ;
        }

        return (new MailMessage)
            ->level('info')
            ->subject( __('notifications.inviteuser.subject'))
            ->greeting( __('notifications.user.greeting', ['username'=>$notifiable->name]))
            ->line( __('notifications.inviteuser.line1', ['sendername'=>$sender]))
            ->line( __('notifications.inviteuser.line2'))
            ->action( __('notifications.inviteuser.action'), route('register.invited', ['language'=>app()->getLocale(),
                                                                                         'invitation' => $this->invitation,
                                                                                         'invited_by'=>Crypt::encryptString($notifiable->email1)]));
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
