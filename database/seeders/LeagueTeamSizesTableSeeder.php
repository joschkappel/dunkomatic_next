<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LeagueTeamSizesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('league_team_sizes')->insert([
        ['size' => '0', 'description' => 'Undefined' ],
        ['size' => '4', 'description' => '4er Runde' ],
        ['size' => '6', 'description' => '6er Runde' ],
        ['size' => '8', 'description' => '8er Runde' ],
        ['size' => '10', 'description' => '10er Runde' ],
        ['size' => '12', 'description' => '12er Runde' ],
        ['size' => '14', 'description' => '14er Runde' ],
        ['size' => '16', 'description' => '16er Runde' ],
        ['size' => '2*4', 'description' => 'Doppel 4er Runde' ],
        ['size' => '2*6', 'description' => 'Doppel 6er Runde' ],
        ['size' => '3*4', 'description' => 'Dreifach 4er Runde' ],
      ]);
    }
}
