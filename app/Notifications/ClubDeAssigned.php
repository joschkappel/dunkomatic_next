<?php

namespace App\Notifications;

use App\Models\Club;
use App\Models\League;
use App\Models\Team;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ClubDeAssigned extends Notification
{
    use Queueable;

    protected League $league;

    protected Club $club;

    protected Team $team;

    protected string $sender_name;

    protected string $receiver_name;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(League $league, Club $club, Team $team, string $sender_name, string $receive_name)
    {
        $this->league = $league;
        $this->club = $club;
        $this->team = $team;
        $this->sender_name = $sender_name;
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
            ->subject(__('notifications.clubdeassigned.subject', ['league' => $this->league->shortname]))
            ->greeting(__('notifications.user.greeting', ['username' => $this->receiver_name]))
            ->line(__('notifications.clubdeassigned.line1', ['team' => $this->club->shortname.$this->team->team_no, 'league' => $this->league->name]))
            ->line(__('notifications.clubdeassigned.line2'))
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
        $lines = '<p>'.__('notifications.clubdeassigned.line1', ['team' => $this->club->shortname.$this->team->team_no, 'league' => $this->league->name]).'</p>';
        $lines .= '<p>'.__('notifications.clubdeassigned.line2').'</p>';

        return [
            'subject' => __('notifications.clubdeassigned.subject', ['league' => $this->league->shortname]),
            'greeting' => __('notifications.user.greeting', ['username' => $this->receiver_name]),
            'lines' => $lines,
            'salutation' => __('notifications.league.salutation', ['leaguelead' => $this->sender_name]),
        ];
    }
}
