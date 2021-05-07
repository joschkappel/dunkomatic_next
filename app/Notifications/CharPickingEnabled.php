<?php

namespace App\Notifications;

use App\Models\Club;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CharPickingEnabled extends Notification
{
    use Queueable;

    protected $club;
    protected $mode;
    protected $season;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Club $club, $mode, $season)
    {
        $this->club = $club;
        $this->season = $season;
        $this->mode = $mode;
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
        if ($this->mode){
            $mode = __('notifications.charpickenabled.open');
        } else {
            $mode = __('notifications.charpickenabled.closed');
        }

        return (new MailMessage)
                    ->level('info')
                    ->subject( __('notifications.charpickenabled.subject', ['region'=>$this->club->region->code,
                                                                            'season'=>$this->season,
                                                                            'mode'=>$mode ]) )
                    ->greeting( __('notifications.user.greeting', ['username'=>$notifiable->name]))
                    ->line(__('notifications.charpickenabled.line1', ['region'=>$this->club->region->name,
                                                                      'season'=>$this->season,
                                                                      'mode'=>$mode]))
                    ->line(__('notifications.charpickenabled.line2'))
                    ->action( __('notifications.charpickenabled.action'), route('club.team.pickchar', ['language'=>app()->getLocale(), 'club' => $this->club] ));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
