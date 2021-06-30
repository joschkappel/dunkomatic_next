<?php

namespace App\Traits;

use App\Enums\LeagueState;
use App\Models\League;
use App\Models\Club;
use App\Models\Team;
use App\Enums\Role;

use App\Notifications\ClubAssigned;
use App\Notifications\ClubDeAssigned;
use App\Notifications\LeagueGamesGenerated;
use App\Notifications\CharPickingEnabled;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

trait LeagueFSM {
    public function open_assignment(League $league) {
        $league->state = LeagueState::Assignment();
        $league->generated_at = null;
        $league->assignment_closed_at = null;
        $league->registration_closed_at = null;
        $league->selection_closed_at = null;
        $league->selection_opened_at = null;
        $league->save();
    }

    public function close_assignment(League $league) {
        $league->state = LeagueState::Registration();
        $league->assignment_closed_at = now();
        $league->save();
        Log::info('League: '.$league->shortname.' Assigment Closed');

        $clubs = $league->clubs;
        $adminname = $league->region->regionadmin->first()->name;
        foreach ($clubs as $c) {
            $member = $c->members()->wherePivot('role_id', Role::ClubLead)->first();

            if (isset($member)) {
                $member->notify(new ClubAssigned($league, $c, $adminname, $member->name));
                $user = $member->user;
                if (isset($user)) {
                    $user->notify(new ClubAssigned($league, $c, $adminname, $user->name));
                }
            }
        }
    }

    public function close_registration(League $league) {
        $league->state = LeagueState::Selection();
        $league->registration_closed_at = now();
        $league->save();
        Log::info('League: '.$league->shortname.' Registration Closed');
    }

    public function close_selection(League $league) {
        $league->state = LeagueState::Freeze();
        $league->selection_closed_at = now();
        $league->save();
        Log::info('League: '.$league->shortname.' Selection Closed');
    }

    public function generate(League $league) {
        $league->state = LeagueState::Scheduling();
        $league->generated_at = now();
        $league->save();
        Log::info('League: '.$league->shortname.' Generation Closed');


        $clubs = $league->teams()->pluck('club_id');
        $adminname = $league->region->regionadmin->first()->name;

        foreach ($clubs as $c){
          $club = Club::find($c);
          $member = $club->members()->wherePivot('role_id',Role::ClubLead)->first();

          if (isset($member)){
            $member->notify(new LeagueGamesGenerated($league, $club, $adminname, $member->name ));
            $user = $member->user;
            if (isset($user)){
              $user->notify(new LeagueGamesGenerated($league, $club, $adminname, $user->name ));
            }
          }

        }
    }

    public function close_scheduling(League $league) {
        $league->state = LeagueState::Live();
        $league->scheduling_closed_at = now();
        $league->save();
        Log::info('League: '.$league->shortname.' Scheduling Closed');
    }


}
