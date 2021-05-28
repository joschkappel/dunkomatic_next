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
          SettingsTableSeeder::class,
          RegionsTableSeeder::class,
          UsersTableSeeder::class,
          TestUserSeeder::class,
          LeagueSizesTableSeeder::class,
          LeagueSizeCharsTableSeeder::class,
          LeagueSizeSchemesTableSeeder::class,
        ]);
        $this->call([
            TestClubsSeeder::class,
            TestLeaguesSeeder::class,
          ]);
    }
}
