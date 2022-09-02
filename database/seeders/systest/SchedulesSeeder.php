<?php

namespace Database\Seeders\systest;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Schedule;

class SchedulesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::unprepared(file_get_contents( __dir__ . '/sql/schedules.sql'));
        DB::unprepared(file_get_contents( __dir__ . '/sql/schedule_events.sql'));
        // Schedule::factory()->events(90)->count(10)->create(['league_size_id'=>5]);

    }
}
