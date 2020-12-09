<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LeagueSizeSchemesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $sizes = [ ['04',2],['06',3],['08',4],['10',5],
                 ['12',6],['14',7],['24',9],
                 ['34',11],['26',10],['16',8] ];

      foreach ($sizes as list($oldsize, $newsize)) {

        $oldrows = DB::connection('dunkv1')->table('team_'.$oldsize.'_scheme')->get();

        foreach ($oldrows as $row) {
          DB::connection('dunknxt')->table('league_size_schemes')->insert([
            'league_size_id'         => $newsize,
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
