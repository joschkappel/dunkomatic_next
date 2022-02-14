<?php

namespace App\Traits;

use App\Enums\LeagueState;
use App\Models\League;
use App\Models\Club;
use App\Enums\Role;

use App\Traits\GameManager;

use App\Notifications\RegisterTeams;
use App\Notifications\SelectTeamLeagueNo;
use App\Notifications\LeagueGamesGenerated;

use Illuminate\Support\Facades\Log;


trait LeagueFSM
{
    use GameManager;

    public function close_assignment(League $league): void
    {
        Log::notice('league state change.', ['league-id' => $league->id, 'old-state' => $league->state->key, 'new-state' => LeagueState::Registration()->key]);
        $league->state = LeagueState::Registration();
        $league->assignment_closed_at = now();
        $league->save();

        $clubs = $league->clubs()->get();
        if ($league->region->regionadmins()->exists()) {
            $adminname = $league->region->regionadmins()->first()->name;

            foreach ($clubs as $c) {
                $member = $c->members()->wherePivot('role_id', Role::ClubLead)->first();

                if (isset($member)) {
                    $member->notify(new RegisterTeams($league, $c, $adminname, $member->name));
                    Log::info('[NOTIFICATION] register teams.', ['league-id' => $league->id, 'member-id' => $member->id]);
                    $user = $member->user;
                    if (isset($user)) {
                        $user->notify(new RegisterTeams($league, $c, $adminname, $user->name));
                        Log::info('[NOTIFICATION] register teams.', ['league-id' => $league->id, 'user-id' => $user->id]);
                    }
                }
            }
        }
    }

    public function close_registration(League $league): void
    {
        Log::notice('league state change.', ['league-id' => $league->id, 'old-state' => $league->state->key, 'new-state' => LeagueState::Selection()->key]);

        $league->state = LeagueState::Selection();
        $league->registration_closed_at = now();
        $league->save();

        $clubs = $league->clubs;

        if ($league->region->regionadmins()->exists()) {
            $adminname = $league->region()->first()->regionadmin()->first()->name;
            foreach ($clubs as $c) {
                $member = $c->members()->wherePivot('role_id', Role::ClubLead)->first();

                if (isset($member)) {
                    $member->notify(new SelectTeamLeagueNo($league, $c, $adminname, $member->name));
                    Log::info('[NOTIFICATION] select league team number.', ['league-id' => $league->id, 'member-id' => $member->id]);

                    $user = $member->user;
                    if (isset($user)) {
                        $user->notify(new SelectTeamLeagueNo($league, $c, $adminname, $user->name));
                        Log::info('[NOTIFICATION] select league team number.', ['league-id' => $league->id, 'user-id' => $user->id]);
                    }
                }
            }
        }
    }

    public function close_selection(League $league): void
    {
        Log::notice('league state change.', ['league-id' => $league->id, 'old-state' => $league->state->key, 'new-state' => LeagueState::Freeze()->key]);

        $league->state = LeagueState::Freeze();
        $league->selection_closed_at = now();
        $league->save();
    }

    public function close_freeze(League $league): void
    {
        Log::notice('league state change.', ['league-id' => $league->id, 'old-state' => $league->state->key, 'new-state' => LeagueState::Scheduling()->key]);

        $league->state = LeagueState::Scheduling();
        $this->create_games($league);
        $league->generated_at = now();
        $league->save();

        $clubs = $league->teams()->pluck('club_id');

        if ($league->region->regionadmins()->exists()) {
            $adminname = $league->region()->first()->regionadmin()->first()->name;

            foreach ($clubs as $c) {
                $club = Club::find($c);
                $member = $club->members()->wherePivot('role_id', Role::ClubLead)->first();

                if (isset($member)) {
                    $member->notify(new LeagueGamesGenerated($league, $club, $adminname, $member->name));
                    Log::info('[NOTIFICATION] league games generated.', ['league-id' => $league->id, 'member-id' => $member->id]);

                    $user = $member->user;
                    if (isset($user)) {
                        $user->notify(new LeagueGamesGenerated($league, $club, $adminname, $user->name));
                        Log::info('[NOTIFICATION] league games generated.', ['league-id'=>$league->id, 'user-id'=>$user->id]);
                    }
                }
            }
        }
    }

    public function close_scheduling(League $league): void
    {
        Log::notice('league state change.', ['league-id' => $league->id, 'old-state' => $league->state->key, 'new-state' => LeagueState::Referees()->key]);

        $league->state = LeagueState::Referees();
        $league->scheduling_closed_at = now();
        $league->save();
    }

    public function close_referees(League $league): void
    {
        Log::notice('league state change.', ['league-id' => $league->id, 'old-state' => $league->state->key, 'new-state' => LeagueState::Live()->key]);

        $league->state = LeagueState::Live();
        $league->referees_closed_at = now();
        $league->save();
    }

    public function open_assignment(League $league): void
    {
        Log::notice('league state change.', ['league-id' => $league->id, 'old-state' => $league->state->key ?? '', 'new-state' => LeagueState::Assignment()->key]);

        $league->state = LeagueState::Assignment();
        $league->generated_at = null;
        $league->assignment_closed_at = null;
        $league->registration_closed_at = null;
        $league->selection_closed_at = null;
        $league->selection_opened_at = null;
        $league->save();
    }

    public function open_registration(League $league): void
    {
        Log::notice('league state change.', ['league-id' => $league->id, 'old-state' => $league->state->key, 'new-state' => LeagueState::Registration()->key]);

        $league->state = LeagueState::Registration();
        $league->registration_closed_at = null;
        $league->save();
    }

    public function open_selection(League $league): void
    {
        Log::notice('league state change.', ['league-id' => $league->id, 'old-state' => $league->state->key, 'new-state' => LeagueState::Selection()->key]);

        $league->state = LeagueState::Selection();
        $league->selection_closed_at = null;
        $league->save();
    }

    public function open_freeze(League $league): void
    {
        Log::notice('league state change.', ['league-id' => $league->id, 'old-state' => $league->state->key, 'new-state' => LeagueState::Freeze()->key]);

        $league->state = LeagueState::Freeze();
        $league->save();
    }

    public function open_scheduling(League $league): void
    {
        Log::notice('league state change.', ['league-id' => $league->id, 'old-state' => $league->state->key, 'new-state' => LeagueState::Scheduling()->key]);

        $league->state = LeagueState::Scheduling();
        $league->scheduling_closed_at = null;
        $league->save();
    }

    public function open_referees(League $league): void
    {
        Log::notice('league state change.', ['league-id' => $league->id, 'old-state' => $league->state->key, 'new-state' => LeagueState::Referees()->key]);

        $league->state = LeagueState::Referees();
        $league->referees_closed_at = null;
        $league->save();
    }

    public function open_setup(League $league): void
    {
        Log::notice('league state change.', ['league-id' => $league->id, 'old-state' => $league->state->key, 'new-state' => LeagueState::Setup()->key]);

        $league->state = LeagueState::Setup();
        $league->save();
        $league->games()->delete();
        $league->teams()->update(['league_id' => null]);
        $league->clubs()->detach();
    }
}
