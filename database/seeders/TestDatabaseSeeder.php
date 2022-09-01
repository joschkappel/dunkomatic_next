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
            base\BouncerSeeder::class,
            base\SettingsTableSeeder::class,
            base\RegionsTableSeeder::class,
            base\LeagueSizesTableSeeder::class,
            base\LeagueSizeCharsTableSeeder::class,
            base\LeagueSizeSchemesTableSeeder::class,
            base\ReportClassesTableSeeder::class,
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
