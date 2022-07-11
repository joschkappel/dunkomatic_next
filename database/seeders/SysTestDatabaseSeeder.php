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
            base\BouncerSeeder::class,
            base\SettingsTableSeeder::class,
            base\RegionsTableSeeder::class,
            base\LeagueSizesTableSeeder::class,
            base\LeagueSizeCharsTableSeeder::class,
            base\LeagueSizeSchemesTableSeeder::class,
            dev\UsersTableSeeder::class,
            base\MessagesTableSeeder::class,
        ]);

        // migrate tables from v1
        $this->call([
            systest\SchedulesSeeder::class,
            systest\MembersSeeder::class,
            systest\ClubsSeeder::class,
            systest\LeaguesSeeder::class,
            fixes\TeamGym::class
        ]);
    }
}
