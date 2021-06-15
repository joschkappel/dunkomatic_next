<?php

namespace App\Observers;

use App\Models\Membership;
use App\Models\Club;
use App\Models\League;
use App\Models\Region;
use App\Enums\Role;
use Bouncer;

class MembershipObserver
{
    /**
     * Handle the Membership "created" event.
     *
     * @param  \App\Models\Membership  $membership
     * @return void
     */
    public function created(Membership $membership)
    {
        if ( $membership->member->isuser ){
            $u =  $membership->member->user;
            if ($membership->membership_type == Club::class ){
                Bouncer::allow($u)->to('manage', Club::find($membership->membership_id));
            } elseif ($membership->membership_type == League::class ){
                Bouncer::allow($u)->to('manage', League::find($membership->membership_id));
            } elseif ($membership->membership_type == Region::class ){
                Bouncer::allow($u)->to('manage', Region::find($membership->membership_id));
            }

            if ($u->isregionadmin){
                Bouncer::assign('regionadmin')->to($u);
            } elseif ($u->isrole(Role::ClubLead())){
                Bouncer::assign('clubadmin')->to($u);
            } elseif ($u->isrole(Role::LeagueLead())){
                Bouncer::assign('leagueadmin')->to($u);
            }
        }
    }

    /**
     * Handle the Membership "updated" event.
     *
     * @param  \App\Models\Membership  $membership
     * @return void
     */
    public function updated(Membership $membership)
    {

    }

    /**
     * Handle the Membership "deleted" event.
     *
     * @param  \App\Models\Membership  $membership
     * @return void
     */
    public function deleted(Membership $membership)
    {
        if ( $membership->member->isuser ){
            if ($membership->member->memberships->where('membership_id',$membership->membership_id)->count() == 0){
                $u =  $membership->member->user;
                if ($membership->membership_type == Club::class ){
                    Bouncer::disallow($u)->to('manage', Club::find($membership->membership_id));
                } elseif ($membership->membership_type == League::class ){
                    Bouncer::disallow($u)->to('manage', League::find($membership->membership_id));
                } elseif ($membership->membership_type == Region::class ){
                    Bouncer::disallow($u)->to('manage', Region::find($membership->membership_id));
                }

                if (! $u->isregionadmin){
                    Bouncer::retract('regionadmin')->from($u);
                } elseif (! $u->isrole(Role::ClubLead())){
                    Bouncer::retract('clubadmin')->from($u);
                } elseif (! $u->isrole(Role::LeagueLead())){
                    Bouncer::retract('leagueadmin')->from($u);
                }
            }
        }

    }

    /**
     * Handle the Membership "restored" event.
     *
     * @param  \App\Models\Membership  $membership
     * @return void
     */
    public function restored(Membership $membership)
    {
        //
    }

    /**
     * Handle the Membership "force deleted" event.
     *
     * @param  \App\Models\Membership  $membership
     * @return void
     */
    public function forceDeleted(Membership $membership)
    {
        //
    }
}
