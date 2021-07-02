<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

use App\Models\League;
use App\Models\Club;

class RegisterTeams extends Notification
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
                    ->subject( __('notifications.registerteams.subject', ['league'=>$this->league->shortname]) )
                    ->greeting( __('notifications.user.greeting', ['username'=>$this->receiver_name]) )
                    ->line( __('notifications.registerteams.line1', ['league'=>$this->league->name]) )
                    ->line( __('notifications.registerteams.line2') )
                    ->action( __('notifications.registerteams.action'), route('club.dashboard', ['language'=>app()->getLocale(), 'club'=>$this->club->id]))
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
        $lines =  '<p>'.__('notifications.registerteams.line1', ['league'=>$this->league->name]).'</p>';
        $lines .= '<p>'.__('notifications.registerteams.line2').'</p>';
        $lines .= '<p><a href="'.url(route('club.dashboard', ['language'=>app()->getLocale(), 'club'=>$this->club->id])).'">'.__('notifications.registerteams.action').'</a></p>';

        return [
            'subject' => __('notifications.registerteams.subject', ['league'=>$this->league->shortname]),
            'greeting' => __('notifications.user.greeting', ['username'=>$this->receiver_name]),
            'lines' => $lines,
            'salutation' => __('notifications.league.salutation', ['leaguelead'=>$this->sender_name]),
        ];
    }
}
