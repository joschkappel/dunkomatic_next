<?php

namespace App\Notifications;
use App\Models\Region;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class CheckRegionSettings extends Notification
{
    use Queueable;

    protected $season;
    protected $region;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($season, Region $region)
    {
        $this->season = $season;
        $this->region = $region;
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
                    ->subject( __('notifications.checkregionsetting.subject'))
                    ->greeting( __('notifications.user.greeting', ['username' => $notifiable->name]) )
                    ->line( __('notifications.checkregionsetting.line1',['season'=>$this->season]) )
                    ->line( __('notifications.checkregionsetting.line2', ['region'=>$this->region->code]) )
                    ->action( __('notifications.checkregionsetting.action') , route('region.edit', ['language'=> app()->getLocale(), 'region'=> $this->region] ) )
                    ->line( __('notifications.checkregionsetting.line3') )
                    ->salutation( __('notifications.app.salutation') );
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
      $lines =  '<p>'.__('notifications.checkregionsetting.line1',['season'=>$this->season]).'</p>';
      $lines .= '<p>'.__('notifications.checkregionsetting.line2', ['region'=>$this->region->code]).' </p>';
      $lines .= '<div><a class="btn btn-primary" href="'.route('region.edit', ['language'=> app()->getLocale(), 'region'=> $this->region] ).'">'.__('notifications.checkregionsetting.action').'</a></div>';
      $lines .= '<p>'.__('notifications.checkregionsetting.line3').' </p>';


      return [
          'subject' => __('notifications.checkregionsetting.subject'),
          'greeting' => __('notifications.user.greeting', ['username' => $notifiable->name]),
          'lines' => $lines,
          'salutation' => __('notifications.app.salutation')
      ];
    }
}
