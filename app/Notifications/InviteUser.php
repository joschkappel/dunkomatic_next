<?php

namespace App\Notifications;

use App\Enums\Role;
use App\Models\Invitation;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class InviteUser extends Notification
{
    use Queueable;

    private User $sender;

    private Invitation $invitation;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Invitation $invitation)
    {
        $this->sender = $invitation->user;
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
        if ($this->sender->member()->exists()) {
            $roles = $this->sender->member->role_in_clubs;
            $roles .= ' '.$this->sender->member->role_in_leagues;
            $roles .= ' '.$this->sender->member->role_in_teams;
            $roles .= ' '.$this->sender->member->role_in_regions;

            $sender = $this->sender->member->name.' ('.Str::limit($roles,25).') ';
        } else {
            $sender = $this->sender->name;
        }

        return (new MailMessage)
            ->level('info')
            ->subject(__('notifications.inviteuser.subject'))
            ->greeting(__('notifications.user.greeting', ['username' => $notifiable->name]))
            ->line(__('notifications.inviteuser.line1', ['sendername' => $sender]))
            ->line(__('notifications.inviteuser.line2'))
            ->action(__('notifications.inviteuser.action'), route('register.invited', ['language' => app()->getLocale(), 'invitation' => $this->invitation]));
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
