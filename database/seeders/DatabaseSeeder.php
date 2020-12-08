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
        $this->call([
          SettingsTableSeeder::class,
          RegionsTableSeeder::class,
          LeagueTeamSizesTableSeeder::class,
          LeagueTeamCharsTableSeeder::class,
          SchedulesTableSeeder::class,
          UsersTableSeeder::class,
        ]);

        $this->call([
          ClubsTableSeeder::class,
          GymsTableSeeder::class,
        ]);

        $this->call([
          LeagueTeamSchemesTableSeeder::class,
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
