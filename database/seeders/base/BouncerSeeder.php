<?php

namespace Database\Seeders\base;

use Silber\Bouncer\BouncerFacade as Bouncer;
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

        /**
         *
         * ABILITIES
         *
         */
        // clubs
        $create_clubs = Bouncer::ability()->firstOrCreate(['name' => 'create-clubs','title' => 'Create/Delete Clubs', ]);
        $update_clubs = Bouncer::ability()->firstOrCreate(['name' => 'update-clubs','title' => 'Update Clubs', ]);
        $view_clubs = Bouncer::ability()->firstOrCreate(['name' => 'view-clubs','title' => 'View Clubs', ]);
        // teams
        $create_teams = Bouncer::ability()->firstOrCreate(['name' => 'create-teams','title' => 'Create/Delete Teams', ]);
        $update_teams = Bouncer::ability()->firstOrCreate(['name' => 'update-teams','title' => 'Update Teams', ]);
        $view_teams = Bouncer::ability()->firstOrCreate(['name' => 'view-teams','title' => 'View Teams', ]);
        // gyms
        $create_gyms = Bouncer::ability()->firstOrCreate(['name' => 'create-gyms','title' => 'Create/Delete gyms', ]);
        $update_gyms = Bouncer::ability()->firstOrCreate(['name' => 'update-gyms','title' => 'Update gyms', ]);
        $view_gyms = Bouncer::ability()->firstOrCreate(['name' => 'view-gyms','title' => 'View gyms', ]);
        // regions
        $create_regions = Bouncer::ability()->firstOrCreate(['name' => 'create-regions','title' => 'Create/Delete regions', ]);
        $update_regions = Bouncer::ability()->firstOrCreate(['name' => 'update-regions','title' => 'Update region details', ]);
        $view_regions = Bouncer::ability()->firstOrCreate(['name' => 'view-regions','title' => 'View regions', ]);
        // leagues
        $create_leagues = Bouncer::ability()->firstOrCreate(['name' => 'create-leagues','title' => 'Create/Delete leagues', ]);
        $update_leagues = Bouncer::ability()->firstOrCreate(['name' => 'update-leagues','title' => 'Update leagues', ]);
        $view_leagues = Bouncer::ability()->firstOrCreate(['name' => 'view-leagues','title' => 'View leagues', ]);
        // games
        $update_games = Bouncer::ability()->firstOrCreate(['name' => 'update-games','title' => 'Update games', ]);
        $view_games = Bouncer::ability()->firstOrCreate(['name' => 'view-games','title' => 'View games', ]);
        // schedules
        $create_schedules = Bouncer::ability()->firstOrCreate(['name' => 'create-schedules','title' => 'Create schedules', ]);
        $update_schedules = Bouncer::ability()->firstOrCreate(['name' => 'update-schedules','title' => 'Update schedules', ]);
        $view_schedules = Bouncer::ability()->firstOrCreate(['name' => 'view-schedules','title' => 'View schedules', ]);
        // members
        $create_members = Bouncer::ability()->firstOrCreate(['name' => 'create-members','title' => 'Create/Delete members', ]);
        $update_members = Bouncer::ability()->firstOrCreate(['name' => 'update-members','title' => 'Update members', ]);
        $view_members = Bouncer::ability()->firstOrCreate(['name' => 'view-members','title' => 'View members', ]);
        // users
        $create_users = Bouncer::ability()->firstOrCreate(['name' => 'create-users','title' => 'Create/Delete users', ]);
        $update_users = Bouncer::ability()->firstOrCreate(['name' => 'update-users','title' => 'Update users', ]);
        $view_users = Bouncer::ability()->firstOrCreate(['name' => 'view-users','title' => 'View users', ]);
        $update_profile = Bouncer::ability()->firstOrCreate(['name' => 'update-profile','title' => 'Update user profile', ]);
        // auth
        $register = Bouncer::ability()->firstOrCreate(['name' => 'register','title' => 'guest registration', ]);

        /**
         *
         * ROLES
         *
         */
        $superadmin = Bouncer::role()->firstOrCreate(['name' => 'superadmin','title' => 'Application Administrator',]);
        Bouncer::allow($superadmin)->everything();

        $regionadmin = Bouncer::role()->firstOrCreate(['name' => 'regionadmin','title' => 'Manages Regions',]);
        Bouncer::allow($regionadmin)->to($create_clubs);
        Bouncer::allow($regionadmin)->to($update_clubs);
        Bouncer::allow($regionadmin)->to($view_clubs);
        Bouncer::allow($regionadmin)->to($view_teams);
        Bouncer::allow($regionadmin)->to($view_gyms);

        Bouncer::allow($regionadmin)->to($update_regions);
        Bouncer::allow($regionadmin)->to($view_regions);

        Bouncer::allow($regionadmin)->to($create_leagues);
        Bouncer::allow($regionadmin)->to($update_leagues);
        Bouncer::allow($regionadmin)->to($view_leagues);

        Bouncer::allow($regionadmin)->to($create_schedules);
        Bouncer::allow($regionadmin)->to($update_schedules);
        Bouncer::allow($regionadmin)->to($view_schedules);

        Bouncer::allow($regionadmin)->to($update_games);
        Bouncer::allow($regionadmin)->to($view_games);

        Bouncer::allow($regionadmin)->to($create_members);
        Bouncer::allow($regionadmin)->to($update_members);
        Bouncer::allow($regionadmin)->to($view_members);

        Bouncer::allow($regionadmin)->to($create_users);
        Bouncer::allow($regionadmin)->to($update_users);
        Bouncer::allow($regionadmin)->to($view_users);

        $clubadmin = Bouncer::role()->firstOrCreate(['name' => 'clubadmin','title' => 'Manages Clubs',]);
        Bouncer::allow($clubadmin)->to($view_regions);
        Bouncer::allow($clubadmin)->to($update_clubs);
        Bouncer::allow($clubadmin)->to($view_clubs);

        Bouncer::allow($clubadmin)->to($create_teams);
        Bouncer::allow($clubadmin)->to($update_teams);
        Bouncer::allow($clubadmin)->to($view_teams);

        Bouncer::allow($clubadmin)->to($create_gyms);
        Bouncer::allow($clubadmin)->to($update_gyms);
        Bouncer::allow($clubadmin)->to($view_gyms);

        Bouncer::allow($clubadmin)->to($view_leagues);
        Bouncer::allow($clubadmin)->to($update_leagues);

        Bouncer::allow($clubadmin)->to($update_games);
        Bouncer::allow($clubadmin)->to($view_games);

        Bouncer::allow($clubadmin)->to($create_members);
        Bouncer::allow($clubadmin)->to($update_members);
        Bouncer::allow($clubadmin)->to($view_members);

        $leagueadmin = Bouncer::role()->firstOrCreate(['name' => 'leagueadmin','title' => 'Manages Leagues',]);
        Bouncer::allow($leagueadmin)->to($view_regions);
        Bouncer::allow($leagueadmin)->to($update_leagues);
        Bouncer::allow($leagueadmin)->to($view_leagues);

        Bouncer::allow($leagueadmin)->to($view_schedules);

        Bouncer::allow($leagueadmin)->to($update_games);
        Bouncer::allow($leagueadmin)->to($view_games);

        Bouncer::allow($leagueadmin)->to($create_members);
        Bouncer::allow($leagueadmin)->to($update_members);
        Bouncer::allow($leagueadmin)->to($view_members);

        $guest = Bouncer::role()->firstOrCreate(['name' => 'guest','title' => 'Guest',]);
        //Bouncer::forbid($guest)->everything();
        Bouncer::allow($guest)->to($view_regions);
        Bouncer::allow($guest)->to($view_clubs);
        Bouncer::allow($guest)->to($view_games);
        Bouncer::allow($guest)->to($view_leagues);
        Bouncer::allow($guest)->to($update_profile);
        Bouncer::allow($guest)->to($view_members);

        $candidate = Bouncer::role()->firstOrCreate(['name' => 'candidate','title' => 'Candidate',]);
        // Bouncer::forbid($candidate)->everything();
        Bouncer::allow($candidate)->to($register);

    }
}
