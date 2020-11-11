<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

use App\Models\League;

class LeagueReportsAvailable extends Notification
{
    use Queueable;

    protected $league;

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
                    ->subject('League reports available: '.$this->league->shortname)
                    ->greeting('Dear '.$notifiable->name)
                    ->line('The game reports for league '.$this->league->name.' have been generated and are ready for you to download.')
                    ->action('Download League Reports', route('league_archive.get', $this->league ) )
                    ->salutation('Thank you for using DunkOmatic !');
    }

}
