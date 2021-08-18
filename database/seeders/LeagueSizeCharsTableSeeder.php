<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LeagueSizeCharsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      // 4er
      DB::table('league_size_chars')->insert([
        ['league_size_id' => 2, 'team_char' => '1', 'created_at' => now() ],
        ['league_size_id' => 2, 'team_char' => '2', 'created_at' => now() ],
        ['league_size_id' => 2, 'team_char' => '3', 'created_at' => now() ],
        ['league_size_id' => 2, 'team_char' => '4', 'created_at' => now() ]
      ]);
      // 6er
      DB::table('league_size_chars')->insert([
        ['league_size_id' => 3, 'team_char' => '1', 'created_at' => now() ],
        ['league_size_id' => 3, 'team_char' => '2', 'created_at' => now() ],
        ['league_size_id' => 3, 'team_char' => '3', 'created_at' => now() ],
        ['league_size_id' => 3, 'team_char' => '4', 'created_at' => now() ],
        ['league_size_id' => 3, 'team_char' => '5', 'created_at' => now() ],
        ['league_size_id' => 3, 'team_char' => '6', 'created_at' => now() ]
      ]);

      // 8er
      DB::table('league_size_chars')->insert([
        ['league_size_id' => 4, 'team_char' => '1', 'created_at' => now() ],
        ['league_size_id' => 4, 'team_char' => '2', 'created_at' => now() ],
        ['league_size_id' => 4, 'team_char' => '3', 'created_at' => now() ],
        ['league_size_id' => 4, 'team_char' => '4', 'created_at' => now() ],
        ['league_size_id' => 4, 'team_char' => '5', 'created_at' => now() ],
        ['league_size_id' => 4, 'team_char' => '6', 'created_at' => now() ],
        ['league_size_id' => 4, 'team_char' => '7', 'created_at' => now() ],
        ['league_size_id' => 4, 'team_char' => '8', 'created_at' => now() ]
      ]);

      // 10er
      DB::table('league_size_chars')->insert([
        ['league_size_id' => 5, 'team_char' => '1', 'created_at' => now() ],
        ['league_size_id' => 5, 'team_char' => '2', 'created_at' => now() ],
        ['league_size_id' => 5, 'team_char' => '3', 'created_at' => now() ],
        ['league_size_id' => 5, 'team_char' => '4', 'created_at' => now() ],
        ['league_size_id' => 5, 'team_char' => '5', 'created_at' => now() ],
        ['league_size_id' => 5, 'team_char' => '6', 'created_at' => now() ],
        ['league_size_id' => 5, 'team_char' => '7', 'created_at' => now() ],
        ['league_size_id' => 5, 'team_char' => '8', 'created_at' => now() ],
        ['league_size_id' => 5, 'team_char' => '9', 'created_at' => now() ],
        ['league_size_id' => 5, 'team_char' => '10', 'created_at' => now() ]
      ]);

      // 12er
      DB::table('league_size_chars')->insert([
        ['league_size_id' => 6, 'team_char' => '1', 'created_at' => now() ],
        ['league_size_id' => 6, 'team_char' => '2', 'created_at' => now() ],
        ['league_size_id' => 6, 'team_char' => '3', 'created_at' => now() ],
        ['league_size_id' => 6, 'team_char' => '4', 'created_at' => now() ],
        ['league_size_id' => 6, 'team_char' => '5', 'created_at' => now() ],
        ['league_size_id' => 6, 'team_char' => '6', 'created_at' => now() ],
        ['league_size_id' => 6, 'team_char' => '7', 'created_at' => now() ],
        ['league_size_id' => 6, 'team_char' => '8', 'created_at' => now() ],
        ['league_size_id' => 6, 'team_char' => '9', 'created_at' => now() ],
        ['league_size_id' => 6, 'team_char' => '10', 'created_at' => now() ],
        ['league_size_id' => 6, 'team_char' => '11', 'created_at' => now() ],
        ['league_size_id' => 6, 'team_char' => '12', 'created_at' => now() ]
      ]);

      // 14er
      DB::table('league_size_chars')->insert([
        ['league_size_id' => 7, 'team_char' => '1', 'created_at' => now() ],
        ['league_size_id' => 7, 'team_char' => '2', 'created_at' => now() ],
        ['league_size_id' => 7, 'team_char' => '3', 'created_at' => now() ],
        ['league_size_id' => 7, 'team_char' => '4', 'created_at' => now() ],
        ['league_size_id' => 7, 'team_char' => '5', 'created_at' => now() ],
        ['league_size_id' => 7, 'team_char' => '6', 'created_at' => now() ],
        ['league_size_id' => 7, 'team_char' => '7', 'created_at' => now() ],
        ['league_size_id' => 7, 'team_char' => '8', 'created_at' => now() ],
        ['league_size_id' => 7, 'team_char' => '9', 'created_at' => now() ],
        ['league_size_id' => 7, 'team_char' => '10', 'created_at' => now() ],
        ['league_size_id' => 7, 'team_char' => '11', 'created_at' => now() ],
        ['league_size_id' => 7, 'team_char' => '12', 'created_at' => now() ],
        ['league_size_id' => 7, 'team_char' => '13', 'created_at' => now() ],
        ['league_size_id' => 7, 'team_char' => '14', 'created_at' => now() ]
      ]);

      // 16er
      DB::table('league_size_chars')->insert([
        ['league_size_id' => 8, 'team_char' => '1', 'created_at' => now() ],
        ['league_size_id' => 8, 'team_char' => '2', 'created_at' => now() ],
        ['league_size_id' => 8, 'team_char' => '3', 'created_at' => now() ],
        ['league_size_id' => 8, 'team_char' => '4', 'created_at' => now() ],
        ['league_size_id' => 8, 'team_char' => '5', 'created_at' => now() ],
        ['league_size_id' => 8, 'team_char' => '6', 'created_at' => now() ],
        ['league_size_id' => 8, 'team_char' => '7', 'created_at' => now() ],
        ['league_size_id' => 8, 'team_char' => '8', 'created_at' => now() ],
        ['league_size_id' => 8, 'team_char' => '9', 'created_at' => now() ],
        ['league_size_id' => 8, 'team_char' => '10', 'created_at' => now() ],
        ['league_size_id' => 8, 'team_char' => '11', 'created_at' => now() ],
        ['league_size_id' => 8, 'team_char' => '12', 'created_at' => now() ],
        ['league_size_id' => 8, 'team_char' => '13', 'created_at' => now() ],
        ['league_size_id' => 8, 'team_char' => '14', 'created_at' => now() ],
        ['league_size_id' => 8, 'team_char' => '15', 'created_at' => now() ],
        ['league_size_id' => 8, 'team_char' => '16', 'created_at' => now() ]
      ]);
    }
}
