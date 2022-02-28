<?php

namespace App\Traits;

use App\Models\Club;
use App\Models\League;
use App\Models\User;
use App\Models\Region;
use App\Enums\Role;

use Illuminate\Support\Facades\Log;
use Silber\Bouncer\BouncerFacade as Bouncer;

trait UserAccessManager
{

    /**
     * set intiial access rights for a new user
     *
     * @param \App\Models\User $user
     * @param \App\Models\Region $region
     * @return void
     */
    public function setInitialAccessRights(User $user, Region $region=null): void
    {
        Bouncer::assign('candidate')->to($user);
        if ( $region != null){
            Bouncer::allow($user)->to(['access'], $region);
        };
        Bouncer::refreshFor($user);
        Log::notice('user candidate role set', ['user'=>$user->id,'roles'=>$user->getRoles()]);
    }

    /**
     * set intiial access rights for a new user based on a linked member
     *
     * @param \App\Models\User $user
     * @return void
     */
    public function cloneMemberAccessRights(User $user): void
    {
        $member = $user->member;

        if (isset($member)){
            $clubs = $member->clubs->unique();
            foreach ($clubs as $c) {
                Bouncer::allow($user)->to(['access'], $c);
            }
            // RBAC - set access to league
            $leagues = $member->leagues->unique();
            foreach ($leagues as $l) {
                Bouncer::allow($user)->to(['access'], $l);
            }
            if ($member->memberships->firstWhere('role_id', Role::ClubLead) != null) {
                Bouncer::assign('clubadmin')->to($user);
            }
            if ($member->memberships->firstWhere('role_id', Role::LeagueLead) != null) {
                Bouncer::assign('leagueadmin')->to($user);
            }
            if ($member->memberships->firstWhere('role_id', Role::RegionLead) != null) {
                Bouncer::assign('regionadmin')->to($user);
            }

            Bouncer::refreshFor($user);
        }
    }

    /**
     * set access rights for approved users
     *
     * @param \App\Models\User $user
     * @param bool $regionadmin
     * @param \App\Models\Region $region
     * @param bool $clubadmin
     * @param array|null $clubs
     * @param bool $leagueadmin
     * @param array|null $leagues
     * @return void
     */
    public function setAccessRights(
        User $user,
        bool $regionadmin=false,
        Region $region,
        bool $clubadmin=false,
        array $clubs=null,
        bool $leagueadmin=false,
        array $leagues=null): void
    {
        // remove all other roles and abilities
        $this->removeAllAccessRights($user);
        $this->approveUser($user);

        $user->allow('access', $region);

        if ( $regionadmin ) {
            $user->assign('regionadmin');
            Log::notice('user regionadmin role set', ['user'=>$user->id]);
        }
        if ( $clubadmin ) {
            $user->assign('clubadmin');
            Log::notice('user clubadmin role set', ['user'=>$user->id]);
        }
        if ( $leagueadmin ) {
            $user->assign('leagueadmin');
            Log::notice('user leagueadmin role set', ['user'=>$user->id]);
        }


        // RBAC - enable club access
        if ( $clubs != null ) {
            foreach ($clubs as $c) {
                Bouncer::allow($user)->to(['access'], Club::find($c));
            }
            Log::notice('user allow access to clubs', ['user'=>$user->id, 'clubs'=>$clubs]);
        };

        // RBAC - enable league access
        if ( $leagues >= null ) {
            foreach ($leagues as $l) {
                Bouncer::allow($user)->to(['access'], League::find($l));
            }
            Log::notice('user allow access to leagues', ['user'=>$user->id, 'leagues'=>$leagues]);
        };

        Bouncer::refreshFor($user);
    }

    /**
     * set access rights for blocked users
     *
     * @param \App\Models\User $user
     * @return void
     */
    public function blockUser(User $user): void
    {
        // remove all other roles and abilities
        $this->removeAllAccessRights($user);

        // assign candidate role
        $user->assign('candidate');
        Log::notice('user candidate role set', ['user'=>$user->id,'roles'=>$user->getRoles()]);

        Bouncer::refreshFor($user);
    }

    /**
     * set access rights for approved users
     *
     * @param \App\Models\User $user
     * @return void
     */
    public function approveUser(User $user): void
    {
        // assign candidate role
        $user->retract('candidate');
        $user->assign('guest');
        $user->allow('manage', $user);
        Log::notice('user guest role set', ['user'=>$user->id,'roles'=>$user->getRoles()]);

        Bouncer::refreshFor($user);
    }

    /**
     * remove all roles and capabilities
     *
     * @param \App\Models\User $user
     * @return void
     */
    public function removeAllAccessRights(User $user): void
    {
        // remove all other roles and abilities
        Bouncer::sync($user)->roles([]);
        Bouncer::sync($user)->abilities([]);
        Bouncer::refresh();
        Log::notice('user all roles and capabilities removed', ['user'=>$user->id,'roles'=>$user->getRoles()]);
    }

}
