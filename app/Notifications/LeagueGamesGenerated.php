<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

use App\Models\League;
use App\Models\Club;

class LeagueGamesGenerated extends Notification
{
    use Queueable;

    protected $league;
    protected $club;
    protected $sender_name;
    protected $receiver_name;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(League $league, Club $club, $sender_name, $receive_name)
    {
      $this->league = $league;
      $this->club = $club;
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
                    ->level('info')
                    ->subject('League games generated: '.$this->league->shortname)
                    ->greeting('Dear '.$this->receiver_name)
                    ->line('The games for league '.$this->league->name.' have been generated and are ready for you ')
                    ->line('to check or edit your home game dates and start times.')
                    ->action('Edit Homegames', route('club.list.homegame',['language'=>app()->getLocale(), 'club' => $this->club ]) )
                    ->line('Thank you for using DunkOmatic !')
                    ->salutation('BR your league-lead '.$this->sender_name);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
      $lines =  '<p>The games for league <b>'.$this->league->name.'</b> have been generated and are ready for you to check or edit your home games dates and start times.</p>';
      $lines .= '<p><a href="'.url( route('club.list.homegame',['language'=>app()->getLocale(), 'club' => $this->club ])).'">Edit Homegames</a></p>';
      $lines .= '<p><a href="'.url( route('club.game.chart',['language'=>app()->getLocale(), 'club' => $this->club ])).'">Homegame Overview</a></p>';

      return [
          'subject' => 'League games generated: '.$this->league->shortname,
          'greeting' => '',
          'lines' => $lines,
          'salutation' => ''
      ];
    }
}
