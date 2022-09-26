<?php

namespace App\Notifications;

use App\Models\Club;
use App\Models\League;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LeagueGamesGenerated extends Notification
{
    use Queueable;

    protected League $league;

    protected Club $club;

    protected string $sender_name;

    protected string $receiver_name;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(League $league, Club $club, string $receive_name)
    {
        $this->league = $league;
        $this->club = $club;
        $this->sender_name = $league->region()->first()->regionadmins()->get(['lastname', 'firstname'])->pluck('name')->implode(',');
        $this->receiver_name = $receive_name;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        if (get_class($notifiable) == User::class) {
            return ['database'];
        } else {
            return ['mail'];
        }
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
                    ->subject(__('notifications.leaguegamesgen.subject', ['league' => $this->league->shortname]))
                    ->greeting(__('notifications.user.greeting', ['username' => $this->receiver_name]))
                    ->line(__('notifications.leaguegamesgen.line1', ['league' => $this->league->name]))
                    ->line(__('notifications.leaguegamesgen.line2'))
                    ->action(__('notifications.leaguegamesgen.action'), route('club.list.homegame', ['language' => app()->getLocale(), 'club' => $this->club]))
                    ->salutation(__('notifications.league.salutation', ['leaguelead' => $this->sender_name]));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $lines = '<p>'.__('notifications.leaguegamesgen.line1', ['league' => $this->league->name]).'</p>';
        $lines .= '<p>'.__('notifications.leaguegamesgen.line2').'</p>';
        $lines .= '<p><a href="'.url(route('club.list.homegame', ['language' => app()->getLocale(), 'club' => $this->club])).'">'.__('notifications.leaguegamesgen.action').'</a></p>';
        $lines .= '<p><a href="'.url(route('club.game.chart', ['language' => app()->getLocale(), 'club' => $this->club])).'">'.__('notifications.leaguegamesgen.action2').'</a></p>';

        return [
            'subject' => __('notifications.leaguegamesgen.subject', ['league' => $this->league->shortname]),
            'greeting' => __('notifications.user.greeting', ['username' => $this->receiver_name]),
            'lines' => $lines,
            'salutation' => __('notifications.league.salutation', ['leaguelead' => $this->sender_name]),
        ];
    }
}
