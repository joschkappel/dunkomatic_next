<?php

namespace App\Console\Commands;

use App\Models\League;
use Illuminate\Console\Command;

class SyncClubAssignments extends Command
{
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
            // delete all assigned clubs
            $l->clubs()->detach();

            // now re-createa assigned based on the teams
            foreach ($l->teams as $t) {
                if ($t->league_no != null) {
                    $l->clubs()->attach($t->club_id, ['league_char' => $t->league_char, 'league_no' => $t->league_no]);
                }
            }
        }
        return Command::SUCCESS;
    }
}
