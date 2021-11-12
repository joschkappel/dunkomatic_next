<?php

namespace Database\Seeders\systest;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClubsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::unprepared(file_get_contents( __dir__ . '/sql/clubs.sql'));
        DB::unprepared(file_get_contents( __dir__ . '/sql/gyms.sql'));

    }
}
