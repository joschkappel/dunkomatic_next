<?php

namespace Database\Seeders\systest;

use App\Models\League;
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
        DB::unprepared(file_get_contents(__DIR__.'/sql/leagues.sql'));
        DB::unprepared(file_get_contents(__DIR__.'/sql/club_league.sql'));
        DB::unprepared(file_get_contents(__DIR__.'/sql/teams.sql'));
        DB::unprepared(file_get_contents(__DIR__.'/sql/games.sql'));

        // createa 20 leagues
        /*         for ($i=0; $i < 10; $i++) {
                    League::factory()->registered(8)->create(['league_size_id'=>5]);
                    League::factory()->selected(10, $i)->create(['league_size_id'=>5]);
                    League::factory()->frozen(10, $i)->create(['league_size_id'=>5]);
                    League::factory()->registered(10, $i)->create(['league_size_id'=>5]);
                } */
    }
}
