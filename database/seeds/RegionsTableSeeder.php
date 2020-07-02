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
          ['id' => 'HBV', 'name' => 'Hessischer Basketball Verband', 'hq' => '', 'created_at' => Carbon::now()],
          ['id' => 'HBVDA', 'name' => 'Bezirk Darmstadt','hq' => 'HBV', 'created_at' => Carbon::now()],
          ['id' => 'HBVF', 'name' => 'Bezirk Frankfurt','hq' => 'HBV', 'created_at' => Carbon::now()],
          ['id' => 'HBVKS', 'name' => 'Bezirk Kassel','hq' => 'HBV', 'created_at' => Carbon::now()],
          ['id' => 'HBVGI', 'name' => 'Bezirk Giesen','hq' => 'HBV', 'created_at' => Carbon::now()]
        ]);
    }
}
