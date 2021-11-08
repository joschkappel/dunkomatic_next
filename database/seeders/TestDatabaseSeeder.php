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
        // $this->call(UsersTableSeeder::class);
        $this->call([
          base\BouncerSeeder::class,
          base\SettingsTableSeeder::class,
          base\RegionsTableSeeder::class,
          dev\UsersTableSeeder::class,
          test\UserSeeder::class,
          base\MessagesTableSeeder::class,
          base\LeagueSizesTableSeeder::class,
          base\LeagueSizeCharsTableSeeder::class,
          base\LeagueSizeSchemesTableSeeder::class,
          base\LeagueStateTableSeeder::class,
        ]);
    }
}
