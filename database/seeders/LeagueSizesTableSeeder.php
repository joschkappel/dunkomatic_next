<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LeagueSizesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('league_sizes')->insert([
        ['id'=>1, 'size' => 0, 'iterations' => 0, 'description' => 'Undefined' ],
        ['id'=>2, 'size' => 4, 'iterations' => 1, 'description' => '4er Runde' ],
        ['id'=>3, 'size' => 6, 'iterations' => 1, 'description' => '6er Runde' ],
        ['id'=>4, 'size' => 8, 'iterations' => 1, 'description' => '8er Runde' ],
        ['id'=>5, 'size' => 10, 'iterations' => 1, 'description' => '10er Runde' ],
        ['id'=>6, 'size' => 12, 'iterations' => 1, 'description' => '12er Runde' ],
        ['id'=>7, 'size' => 14, 'iterations' => 1, 'description' => '14er Runde' ],
        ['id'=>8, 'size' => 16, 'iterations' => 1, 'description' => '16er Runde' ],
        ['id'=>9, 'size' => 4, 'iterations' => 2, 'description' => 'Doppel 4er Runde' ],
        ['id'=>10, 'size' => 6, 'iterations' => 2, 'description' => 'Doppel 6er Runde' ],
        ['id'=>11, 'size' => 4, 'iterations' => 3, 'description' => 'Dreifach 4er Runde' ],
      ]);
    }
}
