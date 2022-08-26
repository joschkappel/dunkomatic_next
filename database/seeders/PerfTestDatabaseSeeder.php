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
          base\BouncerSeeder::class,
          base\SettingsTableSeeder::class,
          base\RegionsTableSeeder::class,
          base\ReportClassesTableSeeder::class,
          dev\UsersTableSeeder::class,
          test\UserSeeder::class,
          base\LeagueSizesTableSeeder::class,
          base\LeagueSizeCharsTableSeeder::class,
          base\LeagueSizeSchemesTableSeeder::class,
        ]);
        $this->call([
            test\ClubsSeeder::class,
            test\LeaguesSeeder::class,
            base\LeagueStateTableSeeder::class,
          ]);
    }
}
