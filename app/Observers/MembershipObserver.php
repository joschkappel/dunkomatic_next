<?php

namespace App\Observers;

use App\Models\Membership;
use App\Models\Club;
use App\Models\League;
use App\Models\Region;
use App\Enums\Role;
use Silber\Bouncer\BouncerFacade as Bouncer;

use Illuminate\Support\Facades\Log;

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
            Log::info('[OBSERVER] membership created - user exists',['membership-id'=>$membership->id]);
            $u =  $membership->member->user;
            if ($membership->membership_type == Club::class ){
                Bouncer::allow($u)->to(['access'], Club::find($membership->membership_id));
                Log::info('[OBSERVER] membership created - allow club access',['membership-id'=>$membership->id, 'club-id'=>$membership->membership_id ]);
            } elseif ($membership->membership_type == League::class ){
                Bouncer::allow($u)->to(['access'], League::find($membership->membership_id));
                Log::info('[OBSERVER] membership created - allow league access',['membership-id'=>$membership->id, 'league-id'=>$membership->membership_id ]);
            } elseif ($membership->membership_type == Region::class ){
                Bouncer::allow($u)->to(['access'], Region::find($membership->membership_id));
                Log::info('[OBSERVER] membership created - allow region access',['membership-id'=>$membership->id, 'region-id'=>$membership->membership_id ]);
            } else {
                Log::error('unknown membership type',['membership-id'=>$membership->id, 'type'=>$membership->membership_type]);
            }

            if ($u->isRole(Role::RegionLead())){
                Bouncer::assign('regionadmin')->to($u);
            } elseif ($u->isRole(Role::ClubLead())){
                Bouncer::assign('clubadmin')->to($u);
            } elseif ($u->isRole(Role::LeagueLead())){
                Bouncer::assign('leagueadmin')->to($u);
            } else {
                Bouncer::assign('guest')->to($u);
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
        if ( $membership->load('member')->member()->first()->isuser ){
            Log::info('[OBSERVER] membership deleted - user exists',['membership-id'=>$membership->id]);
            if ($membership->member->memberships->where('membership_id',$membership->membership_id)->count() == 0){
                $u =  $membership->member->user;
                if ($membership->membership_type == Club::class ){
                    Bouncer::disallow($u)->to('access', Club::find($membership->membership_id));
                    Log::info('[OBSERVER] membership created - disallow club access',['membership-id'=>$membership->id, 'club-id'=>$membership->membership_id ]);
                } elseif ($membership->membership_type == League::class ){
                    Bouncer::disallow($u)->to('access', League::find($membership->membership_id));
                    Log::info('[OBSERVER] membership created - disallow league access',['membership-id'=>$membership->id, 'leauge-id'=>$membership->membership_id ]);
                } elseif ($membership->membership_type == Region::class ){
                    Bouncer::disallow($u)->to('access', Region::find($membership->membership_id));
                    Log::info('[OBSERVER] membership created - disallow region access',['membership-id'=>$membership->id, 'region-id'=>$membership->membership_id ]);
                } else {
                    Log::error('unknown membership type',['membership-id'=>$membership->id, 'type'=>$membership->membership_type]);
                }

                if (! $u->isRole(Role::RegionLead())){
                    Bouncer::retract('regionadmin')->from($u);
                } elseif (! $u->isRole(Role::ClubLead())){
                    Bouncer::retract('clubadmin')->from($u);
                } elseif (! $u->isRole(Role::LeagueLead())){
                    Bouncer::retract('leagueadmin')->from($u);
                }
            }
        }

        $member = $membership->member;

        if ( $member->memberships->count() == 0){
            // none, delete member as well
            $member->delete();
            Log::info('member with no memberships deleted.', ['member-id' => $member->id]);
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
