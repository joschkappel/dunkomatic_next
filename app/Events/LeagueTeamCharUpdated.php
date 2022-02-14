<?php

namespace App\Events;

use App\Models\League;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LeagueTeamCharUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public League $league;
    public string $action;
    public string $ccode;

    /**
     * Create a new event instance.
     *
     * @param \App\Models\League $league
     * @param string $action
     * @param string $ccode
     * @return void
     *
     */
    public function __construct(League $league, string $action='', string $ccode='success')
    {
        $this->league = $league;
        $this->action = $action;
        $this->ccode = $ccode;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     *
     */
    public function broadcastOn()
    {
        return new Channel('user-leagues');
    }
    /**
     * The event's broadcast name.
     *
     * @return string
     *
     */
    public function broadcastAs()
    {
        return 'LeagueCharPickEvent';
    }

}
