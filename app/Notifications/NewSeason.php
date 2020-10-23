<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class NewSeason extends Notification
{
    use Queueable;

    protected $season;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($season)
    {
        $this->season = $season;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
      if ( get_class($notifiable) == 'App\Models\User') {
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
                    ->subject('New Season Start')
                    ->greeting('Dear '.$notifiable->name)
                    ->line('The new season '.$this->season.' has been kicked off in DunkOmatic.')
                    ->line('Some work and fun is ahead of you.')
                    ->line('Stay tuned and watch your messabe board or inbox')
                    ->salutation('BR DunkObot');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
      $lines =  '<p>The new season <b>'.$this->season.'</b> has been kicked off in DunkOmatic.</p>';
      $lines .= '<p>Some work and fun is ahead of you. </p>';
      $lines .= '<p class="text-info">Please watch your message board or inbox.</p>';

      return [
          'subject' => 'New Season Start',
          'greeting' => 'Dear '.$notifiable->name,
          'lines' => $lines,
          'salutation' => 'BR DunkObot',
      ];
    }
}
