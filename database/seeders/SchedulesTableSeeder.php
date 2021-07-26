<?php
namespace Database\Seeders;

use App\Models\LeagueSize;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Region;

class SchedulesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

      $sizes = [ '4'=>2,'6'=>3,'8'=>4,'10'=>5,'12'=>6,'14'=>7,'2*4'=>9,'3*4'=>11,'2*6'=>10,'16'=>8 ];

      // create custom schedule entries, 1 per region
      $regions = Region::all();
      foreach ($regions as $r){
        DB::connection('dunknxt')->table('schedules')->insert([
          'id'     => 100 + $r->id,
          'name'   => 'Custom',
          'region_id' => $r->id,
          'league_size_id' => LeagueSize::UNDEFINED,
          'custom_events' => true,
          'created_at' => now(),
          'eventcolor' => 'red'
        ]);
      }

      $old = DB::connection('dunkv1')->table('schedule_group')->get();

      foreach ($old as $schedule) {
        if ( strpos( $schedule->region, 'XXX' ) === false){
          $region = $schedule->region;
        } else {
          $region = str_replace('XXX', 'HBV', $schedule->region);
        }

        $osize = DB::connection('dunkv1')->table('schedule')->where('group_id',$schedule->group_id)->max('game_day');

        if ($osize > 0 ){
          $size = ($osize / 2)+1;

          if ( $size % 2 != 0){
            $size = "2*".(($osize / 4)+1);
          }

          $newsize = $sizes[strval($size)];
          $custom_events = false;

          DB::connection('dunknxt')->table('schedules')->insert([
            'id'     => $schedule->group_id,
            'name'   => $schedule->group_name,
            'region_id' => Region::where('code',$region)->first()->id,
            'league_size_id' => $newsize,
            'custom_events' => $custom_events,
            'created_at' => now()
          ]);
        }

    }
  }
}
