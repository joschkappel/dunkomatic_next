<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RegionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('regions')->insert([
            ['code' => 'HBV', 'name' => 'Hessischer Basketball Verband', 'hq' => null, 'created_at' => Carbon::now()],
            ['code' => 'HBVDA', 'name' => 'Bezirk Darmstadt', 'hq' => 'HBV', 'created_at' => Carbon::now()],
            ['code' => 'HBVF', 'name' => 'Bezirk Frankfurt', 'hq' => 'HBV', 'created_at' => Carbon::now()],
            ['code' => 'HBVKS', 'name' => 'Bezirk Kassel', 'hq' => 'HBV', 'created_at' => Carbon::now()],
            ['code' => 'HBVGI', 'name' => 'Bezirk Giesen', 'hq' => 'HBV', 'created_at' => Carbon::now()],
        ]);
    }
}
