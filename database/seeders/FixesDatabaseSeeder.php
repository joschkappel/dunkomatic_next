<?php
namespace Database\Seeders;

use Database\Seeders\InactiveClubs;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FixesDatabaseSeeder extends Seeder
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
          fixes\InactiveClubs::class
        ]);

    }
}
