<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UserSeeder::class);

        // load base tables
        $this->call([
            base\SettingsTableSeeder::class,
            dev\UsersTableSeeder::class,
            base\MessagesTableSeeder::class,
        ]);

        // migrate tables from v1
        $this->call([
            dev\ClubsTableSeeder::class,
            dev\GymsTableSeeder::class,
            prod\SchedulesTableSeeder::class,
            prod\ScheduleEventsTableSeeder::class,
            prod\LeaguesTableSeeder::class,
            prod\ClubLeagueTableSeeder::class,
            prod\TeamsTableSeeder::class,
            dev\MembersTableSeeder::class,
            dev\GamesTableSeeder::class,
            base\LeagueStateTableSeeder::class,
        ]);
    }
}
