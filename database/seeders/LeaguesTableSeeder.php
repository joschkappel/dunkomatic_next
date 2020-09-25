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
      $old_league = DB::connection('dunkv1')->table('league')->where('region','!=','')->where('active','1')->get();

      foreach ($old_league as $league) {

        if ($league->group_id == 0){
          $group_id =  NULL;
        } else {
          $group_id = $league->group_id;
        }

        DB::connection('dunknxt')->table('leagues')->insert([
          'id'            => $league->league_id,
          'shortname'     => $league->shortname,
          'region'        => $league->region,
          'name'          => $league->league_name,
          'schedule_id'   => $group_id,
          'active'        => $league->active,
          'above_region'  => $league->above_region,
          'created_at'    => now(),
        ]);

      }
    }
}
