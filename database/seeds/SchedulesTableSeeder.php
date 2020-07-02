<?php

use Illuminate\Database\Seeder;

class SchedulesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $old = DB::connection('dunkv1')->table('schedule_group')->get();

      foreach ($old as $schedule) {
        if ( strpos( $schedule->region, 'XXX' ) === false){
          $active = True;
          $region = $schedule->region;
        } else {
          $active = False;
          $region = str_replace('XXX', 'HBV', $schedule->region);
        }

        $osize = DB::connection('dunkv1')->table('schedule')->where('group_id',$schedule->group_id)->max('game_day');

        $size = ($osize / 2)+1;

        if ( $size % 2 != 0){
          $size = "2*".(($osize / 4)+1);
        }

        DB::connection('dunknxt')->table('schedules')->insert([
          'id'     => $schedule->group_id,
          'name'   => $schedule->group_name,
          'active' => $active,
          'region_id' => $region,
          'size' => $size,
          'created_at' => now()
        ]);
    }
  }
}
