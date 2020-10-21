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
                  ->subject('Team de-assigned from league '.$this->league->shortname)
                  ->greeting('Dear '.$this->receiver_name)
                  ->line('Your team '.$this->club->shortname.$this->team->team_no.' has been removed from league '.$this->league->name.'.')
                  ->line('In case of questions pls check with the league lead')
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
      $lines =  '<p>Your team '.$this->club->shortname.$this->team->team_no.' has been removed from league <b>'.$this->league->name.'</b>.</p>';
      $lines .= '<p>In case of questions pls check with the league lead.</p>';

      return [
          'subject' => 'Team de-assigned from league '.$this->league->shortname,
          'greeting' => 'Dear '.$this->receiver_name,
          'lines' => $lines,
          'salutation' => 'BR your league-lead '.$this->sender_name,
      ];
    }
}
