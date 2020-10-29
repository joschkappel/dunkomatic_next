<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

use App\Models\League;
use App\Models\Club;

class ClubAssigned extends Notification
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
                    ->level('success')
                    ->subject('Club assigned to league '.$this->league->shortname)
                    ->greeting('Dear '.$this->receiver_name)
                    ->line('Your club has been assigned to league '.$this->league->name.'.')
                    ->line('You are ready to register a team with the league now.')
                    ->action('Register Team', route('club.dashboard', ['language'=>app()->getLocale(), 'id'=>$this->club->id]))
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
        $lines =  '<p>Your club has been assigned to league <b>'.$this->league->name.'</b>.</p>';
        $lines .= '<p>You are ready to register a team with the league now.</p>';
        $lines .= '<p><a href="'.url(route('club.dashboard', ['language'=>app()->getLocale(), 'id'=>$this->club->id])).'">Register Team</a></p>';

        return [
            'subject' => 'Club assigned to league '.$this->league->shortname,
            'greeting' => 'Dear '.$this->receiver_name,
            'lines' => $lines,
            'salutation' => 'BR your league-lead '.$this->sender_name,
        ];
    }
}