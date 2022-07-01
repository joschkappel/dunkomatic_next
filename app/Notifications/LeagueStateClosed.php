<?php

namespace App\Notifications;

use Illuminate\Support\Collection;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LeagueStateClosed extends Notification
{
    use Queueable;

    protected string $league_state;
    protected Collection $closed;
    protected Collection $not_closed;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct( string $league_state, Collection $closed, Collection $not_closed)
    {
        $this->league_state = $league_state;
        $this->closed = $closed;
        $this->not_closed = $not_closed;

    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail','database'];
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
                    ->subject(__('notifications.league.state.closed.subject',['phase'=>$this->league_state]))
                    ->greeting( __('notifications.user.greeting', ['username' => $notifiable->name]) )
                    ->line(__('notifications.league.state.closed.line1',['phase'=>$this->league_state]))
                    ->line( $this->closed->pluck('shortname')->implode(', ') ?? 'Keine')
                    ->line(__('notifications.league.state.closed.line2',['phase'=>$this->league_state]))
                    ->line( $this->not_closed->pluck('shortname')->implode(', ') ?? 'Keine')
                    ->line(__('notifications.league.state.closed.line3'))
                    ->salutation( __('notifications.app.salutation') );
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $lines =  '<p>'.__('notifications.league.state.closed.line1',['phase'=>$this->league_state]).'</p>';
        $lines .= '<p>'.($this->closed->pluck('shortname')->implode(', ') ?? 'Keine').'</p>';
        $lines .= '<p>'.__('notifications.league.state.closed.line2',['phase'=>$this->league_state]).'</p>';
        $lines .= '<p>'.( $this->not_closed->pluck('shortname')->implode(', ') ?? 'Keine').'</p>';
        $lines .= '<p>'.__('notifications.league.state.closed.line3').'</p>';


        return [
            'subject' => __('notifications.league.state.closed.subject',['phase'=>$this->league_state]),
            'greeting' => __('notifications.user.greeting', ['username' => $notifiable->name]),
            'lines' => $lines,
            'salutation' => __('notifications.app.salutation')
        ];
    }
}
