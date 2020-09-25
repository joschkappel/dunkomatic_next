<?php

use Illuminate\Database\Seeder;

class LeagueTeamSchemesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $sizes = [ ['04','4'],['06','6'],['08','8'],['10','10'],
                 ['12','12'],['14','14'],['24','2*4'],
                 ['34','3*4'],['26','2*6'],['16','16'] ];

      foreach ($sizes as list($oldsize, $newsize)) {

        $oldrows = DB::connection('dunkv1')->table('team_'.$oldsize.'_scheme')->get();

        foreach ($oldrows as $row) {
          DB::connection('dunknxt')->table('league_team_schemes')->insert([
            'size'         => $newsize,
            'game_day'     => $row->game_day,
            'game_no'     => $row->game_no,
            'team_home'     => $row->team_home,
            'team_guest'     => $row->team_guest,
            'created_at'    => now(),
          ]);
        }
      }
    }
}
