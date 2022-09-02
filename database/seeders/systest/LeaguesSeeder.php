<?php

namespace Database\Seeders\systest;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\League;

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

        // createa 20 leagues
/*         for ($i=0; $i < 10; $i++) {
            League::factory()->registered(8)->create(['league_size_id'=>5]);
            League::factory()->selected(10, $i)->create(['league_size_id'=>5]);
            League::factory()->frozen(10, $i)->create(['league_size_id'=>5]);
            League::factory()->registered(10, $i)->create(['league_size_id'=>5]);
        } */

    }
}
