<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

use App\Models\Club;

class ClubReportsAvailable extends Notification
{
    use Queueable;

    protected $club;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Club $club)
    {
      $this->club = $club;
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
                    ->subject('Club reports available: '.$this->club->shortname)
                    ->greeting('Dear '.$notifiable->name)
                    ->line('The game reports for your club '.$this->club->name.' have been generated and are ready for you to download.')
                    ->action('Download Club Reports', route('club_archive.get', $this->club ) )
                    ->salutation('Thank you for using DunkOmatic !');
    }

}
