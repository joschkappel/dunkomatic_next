<?php

namespace Database\Seeders\systest;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MembersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::unprepared(file_get_contents( __dir__ . '/sql/members.sql'));
        DB::unprepared(file_get_contents( __dir__ . '/sql/memberships.sql'));

    }
}
