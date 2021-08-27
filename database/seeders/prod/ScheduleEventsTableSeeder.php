<?php
namespace Database\Seeders\prod;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ScheduleEventsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $old = DB::connection('dunkv1')->table('schedule')->get();

      foreach ($old as $schedule) {
        DB::connection('dunknxt')->table('schedule_events')->insert([
          'schedule_id'   => $schedule->group_id,
          'game_day' => $schedule->game_day,
          'game_date' => $schedule->game_date,
          'full_weekend' => $schedule->weekend,
          'created_at' => now(),
        ]);
      }
    }
}
