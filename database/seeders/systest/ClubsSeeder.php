<?php

namespace Database\Seeders\systest;

use App\Models\Club;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Silber\Bouncer\BouncerFacade as Bouncer;

class ClubsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::unprepared(file_get_contents(__DIR__.'/sql/clubs.sql'));
        DB::unprepared(file_get_contents(__DIR__.'/sql/gyms.sql'));

        $uid = User::where('name', 'user')->first();
        Bouncer::allow($uid)->to('access', Club::find(25));
        Bouncer::allow($uid)->to('access', Club::find(26));
        Bouncer::refresh();
    }
}
