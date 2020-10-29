<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class MissingLead extends Notification
{
    use Queueable;

    protected $clubs;
    protected $leagues;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($clubs, $leagues)
    {
        $this->clubs = $clubs;
        $this->leagues = $leagues;
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
        $mail = (new MailMessage)
                    ->level('error')
                    ->subject('Missing Admins')
                    ->greeting('Dear '.$notifiable->name);

        if (count($this->clubs) > 0){
          $mail = $mail->line('CLubs without any Admin:');
          foreach ($this->clubs as $c){
            $mail = $mail->line($c);
          }
        }

        if (count($this->leagues)>0){
          $mail = $mail->line('')
                       ->line('Leagues without any Admin: ');
          foreach ($this->leagues as $l){
            $mail = $mail->line($l);
          }
        }


        return $mail;
    }

}