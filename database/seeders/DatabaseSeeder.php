<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\dev\GymsTableSeeder;
use Database\Seeders\dev\ClubsTableSeeder;
use Database\Seeders\dev\MembersTableSeeder;

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
        $this->call([
          BouncerSeeder::class,
          SettingsTableSeeder::class,
          RegionsTableSeeder::class,
          LeagueSizesTableSeeder::class,
          LeagueSizeCharsTableSeeder::class,
          SchedulesTableSeeder::class,
          UsersTableSeeder::class,
          TestUserSeeder::class,
        ]);

        $this->call([
          ClubsTableSeeder::class,
          GymsTableSeeder::class,
        ]);

        $this->call([
          LeagueSizeSchemesTableSeeder::class,
          ScheduleEventsTableSeeder::class,
          LeaguesTableSeeder::class,
          ClubLeagueTableSeeder::class,
        ]);

        $this->call([
          TeamsTableSeeder::class,
          MembersTableSeeder::class,
          GamesTableSeeder::class,
        ]);
    }
}
