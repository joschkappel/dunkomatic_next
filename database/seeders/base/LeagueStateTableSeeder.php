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
                if ( $kpis['assigned'] > 0 ) { $this->open_assignment($l);$l->refresh();}
                if ( $kpis['registered'] > 0 ) { $this->close_assignment($l);$l->refresh();}
                if ( $kpis['charspicked'] > 0 ) { $this->close_registration($l);$l->refresh();}
                if ( $kpis['generated'] > 0 ) { $this->close_freeze($l);$l->refresh();}
                if ( ($kpis['generated']>0) and ($kpis['scheduled'] == $kpis['generated']) ) { $this->close_scheduling($l);$l->refresh();}
                if ( ($kpis['generated']>0) and ($kpis['referees'] == $kpis['generated']) ) { $this->close_referees($l);$l->refresh();}
            } else {
                $this->open_setup($l);
            }
        }

    }
}
