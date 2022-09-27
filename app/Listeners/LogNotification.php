<?php

namespace App\Listeners;

use Illuminate\Notifications\Events\NotificationSent;
use Illuminate\Support\Facades\Log;

class LogNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  NotificationSent  $event
     * @return void
     */
    public function handle(NotificationSent $event)
    {
        Log::debug('notification ', ['event' => $event]);
        if (isset($event->notifiable->id)) {
            Log::debug('[NOTIFICATION] sent.', ['notifiable-id' => $event->notifiable->id, 'notification-id' => $event->notification->id]);
        } else {
            Log::debug('[NOTIFICATION] sent.', ['notifiable-id' => 'unknown', 'notification-id' => $event->notification->id]);
        }
    }
}
