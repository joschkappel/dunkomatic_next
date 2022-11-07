<?php

namespace App\Traits;

use App\Enums\LeagueState;
use App\Enums\Role;
use App\Models\Club;
use App\Models\Game;
use App\Models\League;
use App\Notifications\LeagueGamesGenerated;
use App\Notifications\SelectTeamLeagueNo;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

trait LeagueFSM
{
    use GameManager;

    public function open_char_selection(League $league, $send_email = false): void
    {
        Log::notice('league state change.', ['league-id' => $league->id, 'old-state' => $league->state->key, 'new-state' => LeagueState::Selection()->key]);

        $league->state = LeagueState::Selection();
        $league->registration_closed_at = now();
        $league->save();

        if ($league->is_custom) {
            $this->freeze_league($league);
        } else {
            if ($send_email) {
                $teams = $league->teams;
                foreach ($teams as $t) {
                    $member = $t->club->members()->wherePivot('role_id', Role::ClubLead)->first();

                    if (isset($member)) {
                        $member->notify(new SelectTeamLeagueNo($league, $t->club, $member->name));
                        Log::info('[NOTIFICATION] select league team number.', ['league-id' => $league->id, 'member-id' => $member->id]);

                        $user = $member->user;
                        if (isset($user)) {
                            $user->notify(new SelectTeamLeagueNo($league, $t->club, $user->name));
                            Log::info('[NOTIFICATION] select league team number.', ['league-id' => $league->id, 'user-id' => $user->id]);
                        }
                    }
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

    public function open_game_scheduling(League $league, $send_email = false): void
    {
        Log::notice('league state change.', ['league-id' => $league->id, 'old-state' => $league->state->key, 'new-state' => LeagueState::Scheduling()->key]);

        $league->state = LeagueState::Scheduling();
        $this->create_games($league);
        $league->generated_at = now();
        $league->save();

        if ($send_email) {
            $teams = $league->teams;
            foreach ($teams as $t) {
                $member = $t->club->members()->wherePivot('role_id', Role::ClubLead)->first();

                if (isset($member)) {
                    $member->notify(new LeagueGamesGenerated($league, $t->club, $member->name));
                    Log::info('[NOTIFICATION] league games generated.', ['league-id' => $league->id, 'member-id' => $member->id]);

                    $user = $member->user;
                    if (isset($user)) {
                        $user->notify(new LeagueGamesGenerated($league, $t->club, $user->name));
                        Log::info('[NOTIFICATION] league games generated.', ['league-id' => $league->id, 'user-id' => $user->id]);
                    }
                }
            }
        }
    }

    public function get_unscheduled_games_clubs(League $league): Collection
    {
        if ($league->state->is(LeagueState::Scheduling())) {
            // league is in scheduling, get all clubs that still have emtpy games
            $league->load('games_notime');
            $clubs = $league->games_notime->pluck('club_id_home')->unique();

            return $clubs;
        } else {
            return collect();
        }
    }

    public function open_ref_assignment(League $league): void
    {
        Log::notice('league state change.', ['league-id' => $league->id, 'old-state' => $league->state->key, 'new-state' => LeagueState::Referees()->key]);
        $league->state = LeagueState::Referees();
        $league->scheduling_closed_at = now();
        $league->save();
        // remove all games taht are missing home or guest
    }

    public function golive_league(League $league): void
    {
        Log::notice('league state change.', ['league-id' => $league->id, 'old-state' => $league->state->key, 'new-state' => LeagueState::Live()->key]);

        $league->state = LeagueState::Live();
        $league->referees_closed_at = now();
        $league->save();
    }

    public function reopen_ref_assignment(League $league): void
    {
        Log::notice('league state change.', ['league-id' => $league->id, 'old-state' => $league->state->key ?? '', 'new-state' => LeagueState::Referees()->key]);

        $league->state = LeagueState::Referees();
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

        if ($league->is_custom) {
            $this->reopen_team_registration($league);
        }
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
        // remove: if schedule is removed, than teams and clubs should stay
        //  $league->teams()->update(['league_id' => null]);
        //  $league->clubs()->detach();
    }

    public function restart_league(League $league): void
    {
        Log::notice('league state change.', ['league-id' => $league->id, 'old-state' => $league->state->key, 'new-state' => LeagueState::Registration()->key]);

        $league->state = LeagueState::Registration();
        $league->scheduling_closed_at = null;
        $league->assignment_closed_at = now();
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
            'league_id' => null,
        ]);
    }

    public function start_league(League $league): void
    {
        Log::notice('league state change.', ['league-id' => $league->id, 'old-state' => $league->state->key ?? 'UNSET', 'new-state' => LeagueState::Registration()->key]);

        $league->state = LeagueState::Registration();
        $league->assignment_closed_at = now();
        $league->save();
    }

    public function can_club_edit_game(Club $club, Game $game): bool
    {
        if ($game->league()->first()->state->is(LeagueState::Scheduling())) {
            // can edit if in scheduling
            if ($club->id == $game->club_id_home) {
                // can edit home game for club
                return true;
            } else {
                // no home game for this club
                return false;
            }
        } else {
            // cannot edit if not in scheduling
            return false;
        }
    }

    public function must_have_admin(League $league): bool
    {
        if ($league->state->in([LeagueState::Live(), LeagueState::Referees()])) {
            return true;
        } else {
            return false;
        }
    }

    public function can_register_teams(League $league): bool
    {
        if ($league->state->in([LeagueState::Registration(), LeagueState::Selection()])) {
            return true;
        } else {
            return false;
        }
    }

    public function can_modify_teams(League $league): bool
    {
        if ($league->state->in([LeagueState::Registration(), LeagueState::Selection(), LeagueState::Scheduling(), LeagueState::Freeze()])) {
            return true;
        } else {
            return false;
        }
    }
}
