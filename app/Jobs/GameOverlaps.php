<?php

namespace App\Jobs;

use App\Enums\Role;
use App\Models\Region;
use App\Notifications\ClubOverlappingGames;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GameOverlaps implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Region $region;

    /**
     * Create a new job instance.
     *
     * @param  Region  $region
     * @return void
     */
    public function __construct(Region $region)
    {
        $this->region = $region;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info('[JOB][OVERLAPPING GAMES] started.', ['region-id' => $this->region->id]);
        $clubs = $this->region->clubs()->with('members')->get();
        $game_slot = $this->region->game_slot;
        $min_slot = $game_slot - 1;

        foreach ($clubs as $c) {
            $select = 'SELECT distinct ga.id
               FROM games ga
               JOIN games gb on ga.game_time <= date_add(gb.game_time, INTERVAL '.$min_slot.' minute)
                   and date_add(ga.game_time,interval '.$min_slot.' minute) >= gb.game_time
                   and ga.club_id_home=gb.club_id_home and ga.gym_id = gb.gym_id and ga.game_date = gb.game_date
                   and ga.id != gb.id
               WHERE ga.club_id_home='.$c->id.' ORDER BY ga.game_date DESC, ga.club_id_home ASC';

            $ogames = collect(DB::select($select))->pluck('id');

            if (count($ogames) > 0) {
                Log::warning('[JOB][OVERLAPPING GAMES] found overlapping games.', ['club-id' => $c->id, 'gameslot' => $game_slot, 'count' => count($ogames)]);

                $members = $c->members->load('user')->where('pivot.role_id', Role::ClubLead);
                foreach ($members as $m) {
                    $m->notify(new ClubOverlappingGames($c, count($ogames)));
                    if ($m->user()->exists()) {
                        $m->user->notify(new ClubOverlappingGames($c, count($ogames)));
                    }
                }
                Log::info('[NOTIFICATION][MEMBER] overlapping games.', ['member-id' => $members->pluck('id')]);
            } else {
                Log::info('[JOB][OVERLAPPING GAMES] all games OK.', ['club-id' => $c->id, 'gameslot' => $game_slot]);
            }
        }
    }
}
