<?php

use Illuminate\Database\Seeder;

class LeaguesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $old_league = DB::connection('dunkv1')->table('league')->get();

      foreach ($old_league as $league) {
        if ($league->changeable === 'Y'){
          $changeable = True;
        } else {
          $changeable = False;
        }
        DB::connection('dunknxt')->table('leagues')->insert([
          'id'            => $league->league_id,
          'shortname'     => $league->shortname,
          'region'        => $league->region,
          'name'          => $league->league_name,
          'schedule_id'   => $league->group_id,
          'changeable'    => $changeable,
          'active'        => $league->active,
          'above_region'  => $league->above_region,
          'created_at'    => now(),
        ]);

      }
    }
}
