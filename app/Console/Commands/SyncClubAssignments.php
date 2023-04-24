<?php

namespace App\Console\Commands;

use App\Models\League;
use App\Traits\LeagueTeamManager;
use DB;
use Illuminate\Console\Command;

class SyncClubAssignments extends Command
{
    use LeagueTeamManager;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dmatic:syncclubassignments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync the assigned clubs with the final teams';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $all_chars = config('dunkomatic.league_team_chars');

        foreach (League::all() as $l) {
            // get all assigned clubs with league_nos that are out of bounds
            $clubs = $l->clubs->where('pivot.league_no', '>', $l->size);
            if ($clubs->count() > 0) {
                $this->info('League ' . $l->shortname . ' shuffling league_no for ' . $clubs->count() . ' clubs');
            }
            foreach ($clubs as $c) {
                // now re-align these
                [$league_no, $league_char] = $this->getNextFreeSlot($l);

                DB::table('club_league')
                ->where('league_id', $l->id)
                    ->where('club_id', $c->id)
                    ->where('league_no', $c->pivot->league_no)
                    ->update(['league_char' => $league_char, 'league_no' => $league_no]);
                $this->info('        number switched from ' . $c->pivot->league_no . ' to ' . $league_no);
                $l->refresh();

            }
        }
        return Command::SUCCESS;
    }
}
