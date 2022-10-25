<?php

namespace App\Jobs;

use App\Enums\LeagueState;
use App\Enums\Role;
use App\Models\Club;
use App\Models\League;
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

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info('[JOB][OVERLAPPING GAMES] started.');

        // find all leagues in state scheduling
        $leagues = League::where('state', LeagueState::Scheduling())->with('teams')->get();

        if ($leagues->count() > 0) {
            $clubs = Club::whereIn('id', $leagues->pluck('teams.*.club_id')->flatten())->with('members')->get()->unique();
            Log::info('[JOB][OVERLAPPING GAMES] start work on', ['leagues' => $leagues->count(), 'clubs' => $clubs->count()]);

            foreach ($clubs as $c) {
                // get regional game slot
                $game_slot = $c->region->game_slot;
                $min_slot = $game_slot - 1;

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
        } else {
            Log::notice('[JOB][OVERLAPPING GAMES] stopping, no leagues found');
        }
    }
}
