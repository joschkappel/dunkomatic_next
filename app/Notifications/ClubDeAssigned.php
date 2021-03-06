<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

use App\Models\League;
use App\Models\Club;
use App\Models\Team;

class ClubDeAssigned extends Notification
{
    use Queueable;

    protected $league;
    protected $club;
    protected $team;
    protected $sender_name;
    protected $receiver_name;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(League $league, Club $club, Team $team, $sender_name, $receive_name)
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
                  ->subject( __('notifications.clubdeassigned.subject', ['league'=>$this->league->shortname]) )
                  ->greeting( __('notifications.user.greeting', ['username'=>$this->receiver_name]) )
                  ->line( __('notifications.clubdeassigned.line1', ['team'=>$this->club->shortname.$this->team->team_no, 'league'=>$this->league->name]) )
                  ->line( __('notifications.clubdeassigned.line2') )
                  ->salutation( __('notifications.league.salutation', ['leaguelead'=>$this->sender_name]) );
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
      $lines =  '<p>'.__('notifications.clubdeassigned.line1', ['team'=>$this->club->shortname.$this->team->team_no, 'league'=>$this->league->name]).'</p>';
      $lines .= '<p>'.__('notifications.clubdeassigned.line2').'</p>';

      return [
          'subject' => __('notifications.clubdeassigned.subject', ['league'=>$this->league->shortname]),
          'greeting' => __('notifications.user.greeting', ['username'=>$this->receiver_name]),
          'lines' => $lines,
          'salutation' => __('notifications.league.salutation', ['leaguelead'=>$this->sender_name]),
      ];
    }
}
