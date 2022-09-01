<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TestDatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // load base tables
        $this->call([
            base\SettingsTableSeeder::class,
            dev\UsersTableSeeder::class,
            test\UserSeeder::class,
            base\MessagesTableSeeder::class,
        ]);

        // load test tables
        $this->call([
            base\LeagueStateTableSeeder::class,
            fixes\TeamGym::class,
        ]);
    }
}
