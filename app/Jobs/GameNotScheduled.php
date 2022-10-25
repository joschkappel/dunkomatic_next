<?php

namespace App\Jobs;

use App\Enums\LeagueState;
use App\Enums\Role;
use App\Models\Club;
use App\Models\League;
use App\Notifications\ClubUnscheduledGames;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GameNotScheduled implements ShouldQueue
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
        Log::info('[JOB][UNSCHEDULED GAMES] started.');
        // find all leagues in state scheduling
        $leagues = League::where('state', LeagueState::Scheduling())->with('teams')->get();

        if ($leagues->count() > 0) {
            $clubs = Club::whereIn('id', $leagues->pluck('teams.*.club_id')->flatten())->with('members')->get()->unique();
            Log::info('[JOB][OVERLAPPING GAMES] start work on', ['leagues' => $leagues->count(), 'clubs' => $clubs->count()]);

            foreach ($clubs as $c) {
                $select = 'SELECT distinct ga.id
                FROM games ga
                WHERE ga.club_id_home='.$c->id.' and ga.game_date is not null and ga.game_time is null ORDER BY ga.game_date DESC, ga.club_id_home ASC';

                $ogames = collect(DB::select($select))->pluck('id');

                if (count($ogames) > 0) {
                    Log::warning('[JOB][UNSCHEDULED GAMES] found games with no date and time.', ['club-id' => $c->id, 'count' => count($ogames)]);
                    $members = $c->members->load('user')->where('pivot.role_id', Role::ClubLead);
                    foreach ($members as $m) {
                        $m->notify(new ClubUnscheduledGames($c, count($ogames)));
                        if ($m->user()->exists()) {
                            $m->user->notify(new ClubUnscheduledGames($c, count($ogames)));
                        }
                    }
                    Log::info('[NOTIFICATION][MEMBER] unscheduled games.', ['member-id' => $members->pluck('id')]);
                } else {
                    Log::info('[JOB][UNSCHEDULED GAMES] all games OK.', ['club-id' => $c->id]);
                }
            }
        } else {
            Log::notice('[JOB][UNSCHEDULED GAMES] stopping, no leagues found');
        }
    }
}
