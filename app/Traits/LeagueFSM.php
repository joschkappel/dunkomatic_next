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

    public function open_team_registration(League $league): void
    {
        Log::notice('league state change.', ['league-id' => $league->id, 'old-state' => $league->state->key, 'new-state' => LeagueState::Registration()->key]);
        $league->state = LeagueState::Registration();
        $league->assignment_closed_at = now();
        $league->save();

        $clubs = $league->clubs()->get();
        foreach ($clubs as $c) {
            $member = $c->members()->wherePivot('role_id', Role::ClubLead)->first();

            if (isset($member)) {
                $member->notify(new RegisterTeams($league, $c, $member->name));
                Log::info('[NOTIFICATION] register teams.', ['league-id' => $league->id, 'member-id' => $member->id]);
                $user = $member->user;
                if (isset($user)) {
                    $user->notify(new RegisterTeams($league, $c, $user->name));
                    Log::info('[NOTIFICATION] register teams.', ['league-id' => $league->id, 'user-id' => $user->id]);
                }
            }
        }
    }

    public function open_char_selection(League $league): void
    {
        Log::notice('league state change.', ['league-id' => $league->id, 'old-state' => $league->state->key, 'new-state' => LeagueState::Selection()->key]);

        $league->state = LeagueState::Selection();
        $league->registration_closed_at = now();
        $league->save();

        $clubs = $league->clubs;
        foreach ($clubs as $c) {
            $member = $c->members()->wherePivot('role_id', Role::ClubLead)->first();

            if (isset($member)) {
                $member->notify(new SelectTeamLeagueNo($league, $c, $member->name));
                Log::info('[NOTIFICATION] select league team number.', ['league-id' => $league->id, 'member-id' => $member->id]);

                $user = $member->user;
                if (isset($user)) {
                    $user->notify(new SelectTeamLeagueNo($league, $c, $user->name));
                    Log::info('[NOTIFICATION] select league team number.', ['league-id' => $league->id, 'user-id' => $user->id]);
                }
            }
        }
    }

    public function freeze_league(League $league): void
    {
        Log::notice('league state change.', ['league-id' => $league->id, 'old-state' => $league->state->key, 'new-state' => LeagueState::Freeze()->key]);

        $league->state = LeagueState::Freeze();
        $league->selection_closed_at = now();
        $league->save();
    }

    public function open_game_scheduling(League $league): void
    {
        Log::notice('league state change.', ['league-id' => $league->id, 'old-state' => $league->state->key, 'new-state' => LeagueState::Scheduling()->key]);

        $league->state = LeagueState::Scheduling();
        $this->create_games($league);
        $league->generated_at = now();
        $league->save();

        $clubs = $league->teams()->pluck('club_id');
        foreach ($clubs as $c) {
            $club = Club::find($c);
            $member = $club->members()->wherePivot('role_id', Role::ClubLead)->first();

            if (isset($member)) {
                $member->notify(new LeagueGamesGenerated($league, $club, $member->name));
                Log::info('[NOTIFICATION] league games generated.', ['league-id' => $league->id, 'member-id' => $member->id]);

                $user = $member->user;
                if (isset($user)) {
                    $user->notify(new LeagueGamesGenerated($league, $club, $user->name));
                    Log::info('[NOTIFICATION] league games generated.', ['league-id'=>$league->id, 'user-id'=>$user->id]);
                }
            }
        }
    }

    public function open_ref_assignment(League $league): void
    {
        Log::notice('league state change.', ['league-id' => $league->id, 'old-state' => $league->state->key, 'new-state' => LeagueState::Referees()->key]);

        $league->state = LeagueState::Referees();
        $league->scheduling_closed_at = now();
        $league->save();
    }

    public function golive_league(League $league): void
    {
        Log::notice('league state change.', ['league-id' => $league->id, 'old-state' => $league->state->key, 'new-state' => LeagueState::Live()->key]);

        $league->state = LeagueState::Live();
        $league->referees_closed_at = now();
        $league->save();
    }

    public function reopen_club_assignment(League $league): void
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

    public function reopen_team_registration(League $league): void
    {
        Log::notice('league state change.', ['league-id' => $league->id, 'old-state' => $league->state->key, 'new-state' => LeagueState::Registration()->key]);

        $league->state = LeagueState::Registration();
        $league->registration_closed_at = null;
        $league->save();
    }

    public function reopen_char_selection(League $league): void
    {
        Log::notice('league state change.', ['league-id' => $league->id, 'old-state' => $league->state->key, 'new-state' => LeagueState::Selection()->key]);

        $league->state = LeagueState::Selection();
        $league->selection_closed_at = null;
        $league->save();
    }

    public function refreeze_league(League $league): void
    {
        Log::notice('league state change.', ['league-id' => $league->id, 'old-state' => $league->state->key, 'new-state' => LeagueState::Freeze()->key]);
        $this->delete_games($league);

        $league->state = LeagueState::Freeze();
        $league->save();
    }

    public function reopen_game_scheduling(League $league): void
    {
        Log::notice('league state change.', ['league-id' => $league->id, 'old-state' => $league->state->key, 'new-state' => LeagueState::Scheduling()->key]);

        $league->state = LeagueState::Scheduling();
        $league->scheduling_closed_at = null;
        $league->save();
    }

    public function close_league(League $league): void
    {
        Log::notice('league state change.', ['league-id' => $league->id, 'old-state' => $league->state->key, 'new-state' => LeagueState::Setup()->key]);

        $league->state = LeagueState::Setup();
        $league->assignment_closed_at = null;
        $league->save();
        $league->games()->delete();
        $league->teams()->update(['league_id' => null]);
        $league->clubs()->detach();
    }

    public function restart_league(League $league): void
    {
        Log::notice('league state change.', ['league-id' => $league->id, 'old-state' => $league->state->key, 'new-state' => LeagueState::Assignment()->key]);

        $league->state = LeagueState::Assignment();
        $league->scheduling_closed_at = null;
        $league->assignment_closed_at = null;
        $league->selection_closed_at = null;
        $league->selection_opened_at = null;
        $league->registration_closed_at = null;
        $league->generated_at = null;
        $league->referees_closed_at = null;
        $league->save();
        $league->games()->delete();
        $league->teams()->update([
            'league_char' => null,
            'league_no' => null,
            'league_prev' => $league->shortname,
            'league_id' => null
        ]);
    }
    public function start_league(League $league): void
    {
        Log::notice('league state change.', ['league-id' => $league->id, 'old-state' => $league->state->key ?? 'UNSET', 'new-state' => LeagueState::Assignment()->key]);

        $league->state = LeagueState::Assignment();
        $league->save();
    }
}
