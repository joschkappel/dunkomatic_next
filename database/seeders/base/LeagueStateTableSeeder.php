<?php
namespace Database\Seeders\base;

use Illuminate\Database\Seeder;
use App\Models\League;
use App\Traits\LeagueFSM;

class LeagueStateTableSeeder extends Seeder
{
    use LeagueFSM;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // loop through all leagues
        $leagues = League::all();
        foreach ($leagues as $l){
            if ( ($l->schedule()->exists() ) and ($l->league_size()->exists()) ){
                $kpis = $l->state_count;
                if ( $kpis['registered'] > 0 ) { $this->start_league($l);$l->refresh();}
                if ( $kpis['charspicked'] > 0 ) { $this->open_char_selection($l);$l->refresh();}
                if ( $kpis['generated'] > 0 ) { $this->open_game_scheduling($l);$l->refresh();}
                if ( ($kpis['generated']>0) and ($kpis['scheduled'] == $kpis['generated']) ) { $this->open_ref_assignment($l);$l->refresh();}
                if ( ($kpis['generated']>0) and ($kpis['referees'] == $kpis['generated']) ) { $this->golive_league($l);$l->refresh();}
            } else {
                $this->close_league($l);
            }
        }

    }
}
