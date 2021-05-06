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
                    ->subject( __('notifications.newseason.subject'))
                    ->greeting( __('notifications.user.greeting', ['username' => $notifiable->name]) )
                    ->line( __('notifications.newseason.line1', [ 'season' => $this->season ]) )
                    ->line( __('notifications.newseason.line2') )
                    ->line( __('notifications.newseason.line3') );
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
      $lines =  '<p>'.__('notifications.newseason.line1', [ 'season' => $this->season ]).'</p>';
      $lines .= '<p>'.__('notifications.newseason.line2').' </p>';
      $lines .= '<p class="text-info">'.__('notifications.newseason.line3').'</p>';

      return [
          'subject' => __('notifications.newseason.subject'),
          'greeting' => __('notifications.user.greeting', ['username' => $notifiable->name]),
          'lines' => $lines
      ];
    }
}
