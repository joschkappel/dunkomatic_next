<?php

namespace Database\Seeders;

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
            // fixes\InactiveClubs::class
            // fixes\CustomLeagues::class
            fixes\TeamGym::class,
        ]);
    }
}
