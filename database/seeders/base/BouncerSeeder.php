<?php

namespace Database\Seeders\base;

use Bouncer;
use Illuminate\Database\Seeder;

class BouncerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // abilities
        $manage_regions = Bouncer::ability()->firstOrCreate(['name' => 'manage-regions','title' => 'Manage regions', ]);
        $manage_clubs = Bouncer::ability()->firstOrCreate(['name' => 'manage-clubs','title' => 'Manage clubs', ]);
        $manage_leagues = Bouncer::ability()->firstOrCreate(['name' => 'manage-leagues','title' => 'Manage leagues', ]);
        $update_regions = Bouncer::ability()->firstOrCreate(['name' => 'update-regions','title' => 'Update region details', ]);
        $view_regions = Bouncer::ability()->firstOrCreate(['name' => 'view-regions','title' => 'read-only regions', ]);
        $view_clubs = Bouncer::ability()->firstOrCreate(['name' => 'view-clubs','title' => 'read-only clubs', ]);
        $view_leagues = Bouncer::ability()->firstOrCreate(['name' => 'view-leagues','title' => 'read-only leagues', ]);
        $manage_schedules = Bouncer::ability()->firstOrCreate(['name' => 'manage-schedules','title' => 'CRUD schedules', ]);
        $view_schedules = Bouncer::ability()->firstOrCreate(['name' => 'view-schedules','title' => 'read-only schedules', ]);
        $manage_members = Bouncer::ability()->firstOrCreate(['name' => 'manage-members','title' => 'CRUD members', ]);
        $view_members = Bouncer::ability()->firstOrCreate(['name' => 'view-members','title' => 'read-only members', ]);
        $manage_users = Bouncer::ability()->firstOrCreate(['name' => 'manage-users','title' => 'CRUD users', ]);
        $view_users = Bouncer::ability()->firstOrCreate(['name' => 'view-users','title' => 'read-only users', ]);
        $register = Bouncer::ability()->firstOrCreate(['name' => 'register','title' => 'guest registration', ]);

        // roles
        $superadmin = Bouncer::role()->firstOrCreate(['name' => 'superadmin','title' => 'Application Administrator',]);
        Bouncer::allow('superadmin')->everything();

        $regionadmin = Bouncer::role()->firstOrCreate(['name' => 'regionadmin','title' => 'Region Administrator',]);
        Bouncer::allow($regionadmin)->to($manage_clubs);
        Bouncer::allow($regionadmin)->to($manage_leagues);
        Bouncer::allow($regionadmin)->to($view_clubs);
        Bouncer::allow($regionadmin)->to($view_leagues);
        Bouncer::allow($regionadmin)->to($view_members);
        Bouncer::allow($regionadmin)->to($update_regions);
        Bouncer::allow($regionadmin)->to($manage_members);
        Bouncer::allow($regionadmin)->to($manage_schedules);
        Bouncer::allow($regionadmin)->to($view_schedules);
        Bouncer::allow($regionadmin)->to($manage_users);

        $clubadmin = Bouncer::role()->firstOrCreate(['name' => 'clubadmin','title' => 'Club Administrator',]);
        Bouncer::allow($clubadmin)->to($view_clubs);
        Bouncer::allow($clubadmin)->to($view_leagues);
        Bouncer::allow($clubadmin)->to($view_members);
        Bouncer::allow($clubadmin)->to($manage_members);
        Bouncer::allow($clubadmin)->to($view_schedules);

        $leagueadmin = Bouncer::role()->firstOrCreate(['name' => 'leagueadmin','title' => 'League Administrator',]);
        Bouncer::allow($leagueadmin)->to($view_leagues);
        Bouncer::allow($leagueadmin)->to($view_members);
        Bouncer::allow($leagueadmin)->to($manage_members);
        Bouncer::allow($leagueadmin)->to($view_schedules);

        $user = Bouncer::role()->firstOrCreate(['name' => 'user','title' => 'Application User',]);
        Bouncer::allow($user)->to($view_clubs);
        Bouncer::allow($user)->to($view_leagues);
        Bouncer::allow($user)->to($view_members);
        Bouncer::allow($user)->to($view_schedules);

        $guest = Bouncer::role()->firstOrCreate(['name' => 'guest','title' => 'Guest',]);
        Bouncer::forbid('guest')->everything();
        Bouncer::allow($guest)->to($register);

    }
}
