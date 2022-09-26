<?php

namespace Database\Seeders\systest;

use App\Models\Schedule;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SchedulesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::unprepared(file_get_contents(__DIR__.'/sql/schedules.sql'));
        DB::unprepared(file_get_contents(__DIR__.'/sql/schedule_events.sql'));
        // Schedule::factory()->events(90)->count(10)->create(['league_size_id'=>5]);
    }
}
