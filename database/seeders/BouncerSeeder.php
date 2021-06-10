<?php

namespace Database\Seeders;

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
        $manage_regions = Bouncer::ability()->firstOrCreate(['name' => 'manage-regions','title' => 'CRUD regions', ]);
        $update_regions = Bouncer::ability()->firstOrCreate(['name' => 'update-regions','title' => 'Update region details', ]);
        $ro_regions = Bouncer::ability()->firstOrCreate(['name' => 'ro-regions','title' => 'read-only regions', ]);
        $manage_clubs = Bouncer::ability()->firstOrCreate(['name' => 'manage-clubs','title' => 'CRUD clubs', ]);
        $ro_clubs = Bouncer::ability()->firstOrCreate(['name' => 'ro-clubs','title' => 'read-only clubs', ]);
        $manage_leagues = Bouncer::ability()->firstOrCreate(['name' => 'manage-leagues','title' => 'CRUD leagues', ]);
        $ro_leagues = Bouncer::ability()->firstOrCreate(['name' => 'ro-leagues','title' => 'read-only leagues', ]);
        $manage_schedules = Bouncer::ability()->firstOrCreate(['name' => 'manage-schedules','title' => 'CRUD schedules', ]);
        $ro_schedules = Bouncer::ability()->firstOrCreate(['name' => 'ro-schedules','title' => 'read-only schedules', ]);
        $manage_members = Bouncer::ability()->firstOrCreate(['name' => 'manage-members','title' => 'CRUD members', ]);
        $ro_members = Bouncer::ability()->firstOrCreate(['name' => 'ro-members','title' => 'read-only members', ]);
        $manage_users = Bouncer::ability()->firstOrCreate(['name' => 'manage-users','title' => 'CRUD users', ]);
        $ro_users = Bouncer::ability()->firstOrCreate(['name' => 'ro-users','title' => 'read-only users', ]);

        // roles
        $superadmin = Bouncer::role()->firstOrCreate(['name' => 'superadmin','title' => 'Application Administrator',]);
        Bouncer::allow('superadmin')->everything();

        $regionadmin = Bouncer::role()->firstOrCreate(['name' => 'regionadmin','title' => 'Region Administrator',]);
        Bouncer::allow($regionadmin)->to($update_regions);
        Bouncer::allow($regionadmin)->to($manage_clubs);
        Bouncer::allow($regionadmin)->to($manage_leagues);
        Bouncer::allow($regionadmin)->to($manage_members);
        Bouncer::allow($regionadmin)->to($manage_schedules);
        Bouncer::allow($regionadmin)->to($ro_schedules);
        Bouncer::allow($regionadmin)->to($manage_users);

        $clubadmin = Bouncer::role()->firstOrCreate(['name' => 'clubadmin','title' => 'Club Administrator',]);
        Bouncer::allow($clubadmin)->to($manage_clubs);
        Bouncer::allow($clubadmin)->to($manage_members);
        Bouncer::allow($clubadmin)->to($ro_schedules);

        $leagueadmin = Bouncer::role()->firstOrCreate(['name' => 'leagueadmin','title' => 'League Administrator',]);
        Bouncer::allow($leagueadmin)->to($manage_leagues);
        Bouncer::allow($leagueadmin)->to($manage_members);
        Bouncer::allow($leagueadmin)->to($ro_schedules);

        $user = Bouncer::role()->firstOrCreate(['name' => 'user','title' => 'Application User',]);
        Bouncer::allow($clubadmin)->to($ro_clubs);
        Bouncer::allow($clubadmin)->to($ro_leagues);
        Bouncer::allow($clubadmin)->to($ro_members);
        Bouncer::allow($clubadmin)->to($ro_schedules);

        $guest = Bouncer::role()->firstOrCreate(['name' => 'guest','title' => 'Guest',]);
        Bouncer::forbid('guest')->everything();

    }
}
