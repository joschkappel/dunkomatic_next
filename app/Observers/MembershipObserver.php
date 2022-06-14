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

        $member = $membership->member;

        if ($member->memberships->count() == 0) {
            // none, delete member as well
            // unlink from user
            if ($member->user()->exists()){
                $user = $member->user;
                $user->member()->dissociate();
                $user->save();
            }
            $member->delete();
            Log::info('member with no memberships deleted.', ['member-id' => $member->id]);
        }
    }
}
