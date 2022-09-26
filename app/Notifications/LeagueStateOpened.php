<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Collection;

class LeagueStateOpened extends Notification
{
    use Queueable;

    protected string $league_state;

    protected Collection $opened;

    protected Collection $not_opened;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(string $league_state, Collection $opened, Collection $not_opened)
    {
        $this->league_state = $league_state;
        $this->opened = $opened;
        $this->not_opened = $not_opened;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
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
                    ->subject(__('notifications.league.state.opened.subject', ['phase' => $this->league_state]))
                    ->greeting(__('notifications.user.greeting', ['username' => $notifiable->name]))
                    ->line(__('notifications.league.state.opened.line1', ['phase' => $this->league_state]))
                    ->line($this->opened->pluck('shortname')->implode(', ') ?? 'Keine')
                    ->line(__('notifications.league.state.opened.line2', ['phase' => $this->league_state]))
                    ->line($this->not_opened->pluck('shortname')->implode(', ') ?? 'Keine')
                    ->line(__('notifications.league.state.opened.line3'))
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
        $lines = '<p>'.__('notifications.league.state.opened.line1', ['phase' => $this->league_state]).'</p>';
        $lines .= '<p>'.($this->opened->pluck('shortname')->implode(', ') ?? 'Keine').'</p>';
        $lines .= '<p>'.__('notifications.league.state.opened.line2', ['phase' => $this->league_state]).'</p>';
        $lines .= '<p>'.($this->not_opened->pluck('shortname')->implode(', ') ?? 'Keine').'</p>';
        $lines .= '<p>'.__('notifications.league.state.opened.line3').'</p>';

        return [
            'subject' => __('notifications.league.state.opened.subject', ['phase' => $this->league_state]),
            'greeting' => __('notifications.user.greeting', ['username' => $notifiable->name]),
            'lines' => $lines,
            'salutation' => __('notifications.app.salutation'),
        ];
    }
}
