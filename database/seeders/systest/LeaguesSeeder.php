<?php

namespace Database\Seeders\systest;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LeaguesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::unprepared(file_get_contents( __dir__ . '/sql/leagues.sql'));
        DB::unprepared(file_get_contents( __dir__ . '/sql/club_league.sql'));
        DB::unprepared(file_get_contents( __dir__ . '/sql/teams.sql'));
        DB::unprepared(file_get_contents( __dir__ . '/sql/games.sql'));
    }
}
