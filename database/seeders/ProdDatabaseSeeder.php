<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ProdDatabaseSeeder extends Seeder
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
          base\LeagueSizeSchemesTableSeeder::class
        ]);

        // migrate tables from v1
        $this->call([
          prod\ClubsTableSeeder::class,
          prod\GymsTableSeeder::class,
          prod\SchedulesTableSeeder::class,
          prod\ScheduleEventsTableSeeder::class,
          prod\LeaguesTableSeeder::class,
          prod\ClubLeagueTableSeeder::class,
          prod\TeamsTableSeeder::class,
          prod\MembersTableSeeder::class,
          prod\UsersTableSeeder::class,
          base\MessagesTableSeeder::class,
        ]);
    }
}
