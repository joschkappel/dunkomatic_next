<?php

namespace App\Notifications;

use App\Models\League;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LeagueReportsAvailable extends Notification
{
    use Queueable;

    protected League $league;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(League $league)
    {
        $this->league = $league;
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
                    ->level('info')
                    ->subject(__('notifications.leaguerptavail.subject', ['league' => $this->league->shortname]))
                    ->greeting(__('notifications.user.greeting', ['username' => $notifiable->name]))
                    ->line(__('notifications.leaguerptavail.line', ['league' => $this->league->name]))
                    ->action(__('notifications.leaguerptavail.action'), route('league_archive.get', $this->league));
    }
}
