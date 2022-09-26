<?php

namespace App\Observers;

use App\Enums\Role;
use App\Models\Member;
use App\Models\Membership;
use Illuminate\Support\Facades\Log;

class MembershipObserver
{
    public function saved(Membership $membership)
    {
        $member = $membership->load('member')->member;
        $this->set_roles_for_member($member);
    }

    /**
     * Handle the Membership "deleted" event.
     *
     * @param  \App\Models\Membership  $membership
     * @return void
     */
    public function deleted(Membership $membership)
    {
        $member = $membership->load('member')->member;

        if ($member->memberships->count() == 0) {
            // none, delete member as well
            // unlink from user
            if ($member->user()->exists()) {
                $user = $member->user;
                $user->member()->dissociate();
                $user->save();
            }
            $member->delete();
            Log::info('member with no memberships deleted.', ['member-id' => $member->id]);
        } else {
            $this->set_roles_for_member($member);
        }
    }

    private function set_roles_for_member(Member $member)
    {
        $member->loadMissing(['clubs', 'leagues', 'teams', 'region']);
        $clubs = $member->clubs;
        $leagues = $member->leagues;
        $region = $member->region;
        $teams = $member->teams;

        $member->member_of_clubs = $clubs->pluck('shortname')->unique()->implode(', ');
        $member->member_of_leagues = $leagues->pluck('shortname')->unique()->implode(', ');
        $member->member_of_regions = $region->pluck('code')->implode(', ');
        $member->member_of_teams = $teams->pluck('name')->implode(', ');

        $title = collect();
        foreach ($leagues as $l) {
            $title->push(Role::coerce($l->pivot->role_id)->description.' '.$l->shortname);
        }
        $member->role_in_leagues = $title->implode(', ');

        $title = collect();
        foreach ($teams as $t) {
            $t->load('league', 'club');
            $title->push(($t->league->shortname ?? '').' '.Role::coerce($t->pivot->role_id)->description.' '.$t->name);
        }
        $member->role_in_teams = $title->implode(', ');

        $title = collect();
        foreach ($region as $r) {
            $title->push(Role::coerce($r->pivot->role_id)->description.' '.$r->code);
        }
        $member->role_in_regions = $title->implode(', ');

        $title = collect();
        foreach ($clubs as $c) {
            $title->push(Role::coerce($c->pivot->role_id)->description.' '.$c->shortname);
            if ($c->pivot->role_id == Role::ClubLead) {
                $leagues = $c->load('teams.league')->teams->whereNotNull('league_id')->pluck('league.shortname')->implode(', ');
                if ($leagues != '') {
                    $title->push('('.$leagues.')');
                }
            }
        }
        $member->role_in_clubs = $title->implode(', ');
        $member->save();
    }
}
