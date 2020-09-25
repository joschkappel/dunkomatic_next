<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LeagueTeamCharsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      // 4er
      DB::table('league_team_chars')->insert([
        ['size' => '4', 'team_char' => '1', 'created_at' => now() ],
        ['size' => '4', 'team_char' => '2', 'created_at' => now() ],
        ['size' => '4', 'team_char' => '3', 'created_at' => now() ],
        ['size' => '4', 'team_char' => '4', 'created_at' => now() ]
      ]);
      // 2*4er
      DB::table('league_team_chars')->insert([
        ['size' => '2*4', 'team_char' => '1', 'created_at' => now() ],
        ['size' => '2*4', 'team_char' => '2', 'created_at' => now() ],
        ['size' => '2*4', 'team_char' => '3', 'created_at' => now() ],
        ['size' => '2*4', 'team_char' => '4', 'created_at' => now() ]
      ]);
      // 3*4er
      DB::table('league_team_chars')->insert([
        ['size' => '3*4', 'team_char' => '1', 'created_at' => now() ],
        ['size' => '3*4', 'team_char' => '2', 'created_at' => now() ],
        ['size' => '3*4', 'team_char' => '3', 'created_at' => now() ],
        ['size' => '3*4', 'team_char' => '4', 'created_at' => now() ]
      ]);

      // 6er
      DB::table('league_team_chars')->insert([
        ['size' => '6', 'team_char' => '1', 'created_at' => now() ],
        ['size' => '6', 'team_char' => '2', 'created_at' => now() ],
        ['size' => '6', 'team_char' => '3', 'created_at' => now() ],
        ['size' => '6', 'team_char' => '4', 'created_at' => now() ],
        ['size' => '6', 'team_char' => '5', 'created_at' => now() ],
        ['size' => '6', 'team_char' => '6', 'created_at' => now() ]
      ]);
      // 2*6er
      DB::table('league_team_chars')->insert([
        ['size' => '2*6', 'team_char' => '1', 'created_at' => now() ],
        ['size' => '2*6', 'team_char' => '2', 'created_at' => now() ],
        ['size' => '2*6', 'team_char' => '3', 'created_at' => now() ],
        ['size' => '2*6', 'team_char' => '4', 'created_at' => now() ],
        ['size' => '2*6', 'team_char' => '5', 'created_at' => now() ],
        ['size' => '2*6', 'team_char' => '6', 'created_at' => now() ]
      ]);

      // 8er
      DB::table('league_team_chars')->insert([
        ['size' => '8', 'team_char' => '1', 'created_at' => now() ],
        ['size' => '8', 'team_char' => '2', 'created_at' => now() ],
        ['size' => '8', 'team_char' => '3', 'created_at' => now() ],
        ['size' => '8', 'team_char' => '4', 'created_at' => now() ],
        ['size' => '8', 'team_char' => '5', 'created_at' => now() ],
        ['size' => '8', 'team_char' => '6', 'created_at' => now() ],
        ['size' => '8', 'team_char' => '7', 'created_at' => now() ],
        ['size' => '8', 'team_char' => '8', 'created_at' => now() ]
      ]);

      // 10er
      DB::table('league_team_chars')->insert([
        ['size' => '10', 'team_char' => '1', 'created_at' => now() ],
        ['size' => '10', 'team_char' => '2', 'created_at' => now() ],
        ['size' => '10', 'team_char' => '3', 'created_at' => now() ],
        ['size' => '10', 'team_char' => '4', 'created_at' => now() ],
        ['size' => '10', 'team_char' => '5', 'created_at' => now() ],
        ['size' => '10', 'team_char' => '6', 'created_at' => now() ],
        ['size' => '10', 'team_char' => '7', 'created_at' => now() ],
        ['size' => '10', 'team_char' => '8', 'created_at' => now() ],
        ['size' => '10', 'team_char' => '9', 'created_at' => now() ],
        ['size' => '10', 'team_char' => '10', 'created_at' => now() ]
      ]);

      // 12er
      DB::table('league_team_chars')->insert([
        ['size' => '12', 'team_char' => '1', 'created_at' => now() ],
        ['size' => '12', 'team_char' => '2', 'created_at' => now() ],
        ['size' => '12', 'team_char' => '3', 'created_at' => now() ],
        ['size' => '12', 'team_char' => '4', 'created_at' => now() ],
        ['size' => '12', 'team_char' => '5', 'created_at' => now() ],
        ['size' => '12', 'team_char' => '6', 'created_at' => now() ],
        ['size' => '12', 'team_char' => '7', 'created_at' => now() ],
        ['size' => '12', 'team_char' => '8', 'created_at' => now() ],
        ['size' => '12', 'team_char' => '9', 'created_at' => now() ],
        ['size' => '12', 'team_char' => '10', 'created_at' => now() ],
        ['size' => '12', 'team_char' => '11', 'created_at' => now() ],
        ['size' => '12', 'team_char' => '12', 'created_at' => now() ]
      ]);

      // 14er
      DB::table('league_team_chars')->insert([
        ['size' => '14', 'team_char' => '1', 'created_at' => now() ],
        ['size' => '14', 'team_char' => '2', 'created_at' => now() ],
        ['size' => '14', 'team_char' => '3', 'created_at' => now() ],
        ['size' => '14', 'team_char' => '4', 'created_at' => now() ],
        ['size' => '14', 'team_char' => '5', 'created_at' => now() ],
        ['size' => '14', 'team_char' => '6', 'created_at' => now() ],
        ['size' => '14', 'team_char' => '7', 'created_at' => now() ],
        ['size' => '14', 'team_char' => '8', 'created_at' => now() ],
        ['size' => '14', 'team_char' => '9', 'created_at' => now() ],
        ['size' => '14', 'team_char' => '10', 'created_at' => now() ],
        ['size' => '14', 'team_char' => '11', 'created_at' => now() ],
        ['size' => '14', 'team_char' => '12', 'created_at' => now() ],
        ['size' => '14', 'team_char' => '13', 'created_at' => now() ],
        ['size' => '14', 'team_char' => '14', 'created_at' => now() ]
      ]);

      // 16er
      DB::table('league_team_chars')->insert([
        ['size' => '16', 'team_char' => '1', 'created_at' => now() ],
        ['size' => '16', 'team_char' => '2', 'created_at' => now() ],
        ['size' => '16', 'team_char' => '3', 'created_at' => now() ],
        ['size' => '16', 'team_char' => '4', 'created_at' => now() ],
        ['size' => '16', 'team_char' => '5', 'created_at' => now() ],
        ['size' => '16', 'team_char' => '6', 'created_at' => now() ],
        ['size' => '16', 'team_char' => '7', 'created_at' => now() ],
        ['size' => '16', 'team_char' => '8', 'created_at' => now() ],
        ['size' => '16', 'team_char' => '9', 'created_at' => now() ],
        ['size' => '16', 'team_char' => '10', 'created_at' => now() ],
        ['size' => '16', 'team_char' => '11', 'created_at' => now() ],
        ['size' => '16', 'team_char' => '12', 'created_at' => now() ],
        ['size' => '16', 'team_char' => '13', 'created_at' => now() ],
        ['size' => '16', 'team_char' => '14', 'created_at' => now() ],
        ['size' => '16', 'team_char' => '15', 'created_at' => now() ],
        ['size' => '16', 'team_char' => '16', 'created_at' => now() ]
      ]);
    }
}
