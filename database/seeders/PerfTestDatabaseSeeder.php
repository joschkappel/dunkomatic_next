<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PerfTestDatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        $this->call([
            base\SettingsTableSeeder::class,
            dev\UsersTableSeeder::class,
            test\UserSeeder::class,
        ]);
        $this->call([
            test\ClubsSeeder::class,
            test\LeaguesSeeder::class,
            base\LeagueStateTableSeeder::class,
        ]);
    }
}
