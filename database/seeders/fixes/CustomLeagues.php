<?php

namespace Database\Seeders\fixes;

use App\Models\League;
use App\Models\Region;
use App\Traits\LeagueTeamManager;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomLeagues extends Seeder
{
    use LeagueTeamManager;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // only for region HBV-F !!!
        $leagues = Region::where('code','HBVF')->first()->leagues;
        $schedule = Region::where('code','HBVF')->first()->schedules->where('custom_events',true)->first();

        foreach ($leagues as $l){
            // check if undefined
            if ($l->is_not_ready){
                // set custom schedule
                $l->update(['schedule_id'=>$schedule->id]);

                // check if it got  teams
                if ($l->teams()->exists()){
                    foreach ($l->teams as $t){
                        list($league_no, $league_char) = $this->get_custom_league_league_no($l, $t);
                        $t->update([
                            'league_no' => $league_no,
                            'league_char' => $league_char
                        ]);
                    }
                }

            }
        }
    }
}
