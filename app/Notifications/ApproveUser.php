<?php

namespace App\Notifications;

use App\Models\User;
use App\Models\Club;
use App\Models\League;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class ApproveUser extends Notification
{
    use Queueable;

    private $radmin_user;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $radmin_user)
    {
      $this->radmin_user = $radmin_user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
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
            ->subject( __('notifications.approveuser.subject') )
            ->greeting( __('notifications.user.greeting', ['username' => $notifiable->name]) )
            ->line( __('notifications.approveuser.line1', ['region'=>$notifiable->region->name]) )
            ->line( __('notifications.approveuser.line2' ));
    }
    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $ca = Str::replaceLast(',', ' '.__('and'), Club::whereIn('id', $notifiable->getAbilities()->where('entity_type', Club::class)->pluck('entity_id') )->pluck('shortname')->implode(', ')) ?? '--';
        $la = Str::replaceLast(',', ' '.__('and'), League::whereIn('id', $notifiable->getAbilities()->where('entity_type', League::class)->pluck('entity_id') )->pluck('shortname')->implode(', ')) ?? '--';

        $lines = __('notifications.welcome.line1', ['userroles'=>Str::replaceLast(',', ' '.__('and'), $notifiable->getRoles()->implode(', ')), 'region'=>$notifiable->region->name]);
        $lines .= __('notifications.welcome.line2');
        $lines .= __('notifications.welcome.line3', ['clubs'=>$ca , 'leagues'=>$la ]);

        return [
            'greeting' => __('notifications.user.greeting', ['username'=>$notifiable->name]),
            'subject' => __('notifications.welcome.subject'),
            'lines' => $lines,
            'salutation' => __('notifications.app.salutation'),
        ];
    }

}
