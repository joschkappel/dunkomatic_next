<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

use App\Models\Member;
use App\Models\Club;

class InvalidEmail extends Notification
{
    use Queueable;

    protected $cc;
    protected $emaillist;
    protected $club;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Club $club, Member $cc, $emaillist)
    {
        $this->cc = $cc;
        $this->emaillist = $emaillist;
        $this->club = $club;
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
                    ->cc($this->cc->email1)
                    ->level('error')
                    ->subject( __('notifications.invalidemail.subject') )
                    ->greeting( __('notifications.user.greeting', ['username'=>$notifiable->name]) )
                    ->line(__('notifications.invalidemail.line'));
        foreach ($this->emaillist as $l){
          $mail = $mail->line($l);
        };
        $mail = $mail->action(__('notifications.invalidemail.action'),route('club.dashboard', ['language'=>app()->getLocale(), 'id'=>$this->club->id]));

        return $mail;
    }

}
