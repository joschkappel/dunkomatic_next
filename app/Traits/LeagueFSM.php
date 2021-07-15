<?php

namespace App\Traits;

use App\Enums\LeagueState;
use App\Models\League;
use App\Models\Club;
use App\Enums\Role;

use App\Notifications\RegisterTeams;
use App\Notifications\SelectTeamLeagueNo;
use App\Notifications\LeagueGamesGenerated;

use Illuminate\Support\Facades\Log;


trait LeagueFSM {
    public function close_assignment(League $league) {
        $league->state = LeagueState::Registration();
        $league->assignment_closed_at = now();
        $league->save();
        Log::info('League: '.$league->shortname.' Changed to Registration');

        $clubs = $league->clubs;
        $adminname = $league->region->regionadmin->first()->name;
        foreach ($clubs as $c) {
            $member = $c->members()->wherePivot('role_id', Role::ClubLead)->first();

            if (isset($member)) {
                $member->notify(new RegisterTeams($league, $c, $adminname, $member->name));
                $user = $member->user;
                if (isset($user)) {
                    $user->notify(new RegisterTeams($league, $c, $adminname, $user->name));
                }
            }
        }
    }

    public function close_registration(League $league) {
        $league->state = LeagueState::Selection();
        $league->registration_closed_at = now();
        $league->save();
        Log::info('League: '.$league->shortname.' Changed to Selection');

        $clubs = $league->clubs;
        $adminname = $league->region->regionadmin->first()->name;
        foreach ($clubs as $c) {
            $member = $c->members()->wherePivot('role_id', Role::ClubLead)->first();

            if (isset($member)) {
                $member->notify(new SelectTeamLeagueNo($league, $c, $adminname, $member->name));
                $user = $member->user;
                if (isset($user)) {
                    $user->notify(new SelectTeamLeagueNo($league, $c, $adminname, $user->name));
                }
            }
        }        
    }

    public function close_selection(League $league) {
        $league->state = LeagueState::Freeze();
        $league->selection_closed_at = now();
        $league->save();
        Log::info('League: '.$league->shortname.' Changed to Freeze');         
    }

    public function close_freeze(League $league) {
        $league->state = LeagueState::Scheduling();
        $league->generated_at = now();
        $league->save();

        Log::info('League: '.$league->shortname.' Changed to Scheduling');

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
        Log::info('League: '.$league->shortname.' changed to Live');
    }

    public function open_assignment(League $league) {
        $league->state = LeagueState::Assignment();
        $league->generated_at = null;
        $league->assignment_closed_at = null;
        $league->registration_closed_at = null;
        $league->selection_closed_at = null;
        $league->selection_opened_at = null;
        $league->save();
        Log::info('League: '.$league->shortname.' Assignment (Re)-Opened');
    }
    
    public function open_registration(League $league) {
        $league->state = LeagueState::Registration();
        $league->registration_closed_at = null;
        $league->save();
        Log::info('League: '.$league->shortname.' Registration (Re)-Opened');
    }    

    public function open_selection(League $league) {
        $league->state = LeagueState::Selection();
        $league->selection_closed_at = null;
        $league->save();
        Log::info('League: '.$league->shortname.' Selection (Re)-Opened');
    }    

    public function open_freeze(League $league) {
        $league->state = LeagueState::Freeze();
        $league->save();
        Log::info('League: '.$league->shortname.' Freeze (Re)-Opened');
    }        
        
    public function open_scheduling(League $league) {
        $league->state = LeagueState::Scheduling();
        $league->scheduling_closed_at = null;
        $league->save();
        Log::info('League: '.$league->shortname.' Scheduling (Re)-Opened');

    }






}
