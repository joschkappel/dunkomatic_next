<?php

namespace App\Notifications;

use App\Enums\ReportFileType;
use App\Models\Club;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ClubReportsAvailable extends Notification
{
    use Queueable;

    protected Club $club;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Club $club)
    {
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
        return (new MailMessage)
                    ->level('info')
                    ->subject(__('notifications.clubrptavail.subject', ['club' => $this->club->shortname]))
                    ->greeting(__('notifications.user.greeting', ['username' => $notifiable->name]))
                    ->line(__('notifications.clubrptavail.line', ['club' => $this->club->name]))
                    ->action(__('notifications.clubrptavail.action'), route('club_archive.get', ['club' => $this->club, 'format' => ReportFileType::None]));
    }
}
