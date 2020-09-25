<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class RegionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('regions')->insert([
          ['code' => 'HBV', 'name' => 'Hessischer Basketball Verband', 'hq' => '', 'created_at' => Carbon::now()],
          ['code' => 'HBVDA', 'name' => 'Bezirk Darmstadt','hq' => 'HBV', 'created_at' => Carbon::now()],
          ['code' => 'HBVF', 'name' => 'Bezirk Frankfurt','hq' => 'HBV', 'created_at' => Carbon::now()],
          ['code' => 'HBVKS', 'name' => 'Bezirk Kassel','hq' => 'HBV', 'created_at' => Carbon::now()],
          ['code' => 'HBVGI', 'name' => 'Bezirk Giesen','hq' => 'HBV', 'created_at' => Carbon::now()]
        ]);
    }
}
