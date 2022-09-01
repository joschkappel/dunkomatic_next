<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\LeagueSize;

class LeagueSizesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('league_sizes')->insert([
        ['id'=>LeagueSize::UNDEFINED, 'size' => 0, 'description' => 'Undefined' ],
        ['id'=>2, 'size' => 4, 'description' => '4 Teams' ],
        ['id'=>3, 'size' => 6, 'description' => '6 Teams' ],
        ['id'=>4, 'size' => 8, 'description' => '8 Teams' ],
        ['id'=>5, 'size' => 10, 'description' => '10 Teams' ],
        ['id'=>6, 'size' => 12, 'description' => '12 Teams' ],
        ['id'=>7, 'size' => 14, 'description' => '14 Teams' ],
        ['id'=>8, 'size' => 16, 'description' => '16 Teams' ]
      ]);
    }
}
