<?php

namespace App\Notifications;

use App\Models\Club;
use App\Models\User;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ClubUnscheduledGames extends Notification
{
    use Queueable;

    protected Club $club;
    protected int $games_count;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Club $club, int $games_count)
    {
        $this->club = $club;
        $this->games_count = $games_count;
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
            ->error()
            ->subject(__('notifications.unscheduledgames.subject'))
            ->greeting(__('notifications.user.greeting', ['username' => $notifiable->name]))
            ->line(__('notifications.unscheduledgames.line1', ['club' => $this->club->shortname, 'games_count' => $this->games_count]))
            ->action(__('notifications.unscheduledgames.action'), route('club.list.homegame', ['language' => app()->getLocale(), 'club' => $this->club]))
            ->line(__('notifications.unscheduledgames.line2', ['gametimecolumn'=> __('game.game_time') ]))
            ->salutation(__('notifications.app.salutation'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $lines =  '<p>' . __('notifications.unscheduledgames.line1', ['club' => $this->club->shortname, 'games_count' => $this->games_count]) . '</p>';
        $lines .= '<p><a href="'.url(route('club.list.homegame', ['language'=>app()->getLocale(), 'club'=>$this->club->id])).'">'.__('notifications.overlappinggames.action').'</a></p>';
        $lines .= '<p>' . __('notifications.unscheduledgames.line2', ['gametimecolumn'=> __('game.game_time') ]) . '</p>';

        return [
            'subject' => __('notifications.unscheduledgames.subject'),
            'greeting' => __('notifications.user.greeting', ['username' => $notifiable->name]),
            'lines' => $lines,
            'salutation' => __('notifications.app.salutation'),
        ];
    }
}
