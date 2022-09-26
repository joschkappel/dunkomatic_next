<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SysTestDatabaseSeeder extends Seeder
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
            systest\SchedulesSeeder::class,
            systest\MembersSeeder::class,
            systest\ClubsSeeder::class,
            systest\LeaguesSeeder::class,
            fixes\TeamGym::class,
        ]);
    }
}
