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
          base\BouncerSeeder::class,
          base\SettingsTableSeeder::class,
          base\RegionsTableSeeder::class,
          base\LeagueSizesTableSeeder::class,
          base\LeagueSizeCharsTableSeeder::class,
          base\LeagueSizeSchemesTableSeeder::class,
          dev\UsersTableSeeder::class,
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
          prod\GamesTableSeeder::class,
          base\LeagueStateTableSeeder::class,
        ]);
    }
}
