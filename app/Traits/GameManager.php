<?php

namespace App\Traits;

use App\Enums\LeagueAgeType;
use App\Models\Game;
use App\Models\League;
use App\Models\Team;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;

trait GameManager
{
    /**
     * deletes all games for a league in the DB
     *
     * @param  League  $league
     * @return void
     */
    public function delete_games(League $league): void
    {
        $league->games()->delete();
        $league->refresh();
        Log::notice('games deleted.', ['league-id' => $league->id]);
    }

    /**
     * creates games for a league in the DB
     *
     * @param  League  $league
     * @return void
     */
    public function create_games(League $league): void
    {
        Log::info('creating games for league.', ['league-id' => $league->id]);
        // get size
        $league->load('schedule', 'league_size');
        // get scheme
        if ($league->schedule->custom_events) {
            $scheme = $league->league_size->schemes()->get();
            $gdate_by_day = collect([]);
        } else {
            $scheme = $league->schedule->schemes()->get();
            // get game days and dates
            $gdate_by_day = $league->schedule->events()->orderBy('game_day')->pluck('game_date', 'game_day');
            $weekend_by_day = $league->schedule->events()->orderBy('game_day')->pluck('full_weekend', 'game_day');
        }
        $iterations = $league->schedule->iterations ?? 1;
        $max_gday = $scheme->max('game_day') ?? 1;
        $g_perday = ($league->league_size->size / 2) ?? 1;

        // get teams
        $teams = $league->teams()->with('club', 'gym')->get();

        for ($i = 0; $i < $iterations; $i++) {
            foreach ($scheme as $s) {
                $gdate = $gdate_by_day[$s->game_day + ($max_gday * $i)] ?? 'notset';

                if ($gdate != 'notset') {
                    $gday = CarbonImmutable::parse($gdate);
                    $full_weekend = $weekend_by_day[$s->game_day + ($max_gday * $i)];

                    Log::debug('[GAME GENERATION] working on game day', ['league-id' => $league->id, 'game-day' => $s->game_day]);
                    $hteam = $teams->firstWhere('league_no', $s->team_home);
                    $gteam = $teams->firstWhere('league_no', $s->team_guest);

                    if ( ( isset($hteam)) and
                         ( isset($gteam)) ) {
                        $g = [];
                        $g['region_id_league'] = $league->region->id;
                        $g['game_plandate'] = $gday;

                        if ($full_weekend) {
                            if (isset($hteam['preferred_game_day'])) {
                                $pref_gday = $hteam['preferred_game_day'] % 7;
                                $g['game_date'] = $gday->subDay(3)->next($pref_gday);
                            } else {
                                $g['game_date'] = $gday;
                            }
                        } else {
                            $g['game_date'] = $gday;
                        }

                        if ($league->age_type->in([LeagueAgeType::Junior(), LeagueAgeType::Mini()])) {
                            $g['referee_1'] = '****';
                        }

                        $g['team_char_home'] = $s->team_home;
                        $g['team_char_guest'] = $s->team_guest;

                        if (isset($hteam)) {
                            $g['game_time'] = $hteam['preferred_game_time'];
                            $g['gym_id'] = $hteam->gym()->exists() ? $hteam->gym->id : $hteam->club->gyms()->first()->id;
                            $g['club_id_home'] = $hteam['club']['id'];
                            $g['region_id_home'] = $hteam->club->region->id;
                            $g['team_id_home'] = $hteam['id'];
                        }

                        if (isset($gteam)) {
                            $g['club_id_guest'] = $gteam['club']['id'];
                            $g['team_id_guest'] = $gteam['id'];
                            $g['region_id_guest'] = $gteam->club->region->id;
                        }

                        //Log::debug(print_r($g, true));
                        Game::updateOrCreate(['league_id' => $league->id, 'game_no' => $s->game_no + ($max_gday * $i * $g_perday)], $g);
                    } else {
                        Log::notice('[GAME GENERATION] home or guest missing', ['league-id' => $league->id, 'game-no' => $s->game_no]);
                    }
                } else {
                    Log::warning('[GAME GENERATION] Gameday not set', ['league-id' => $league->id, 'game-day' => $s->game_day]);
                }
            }
        }
        Log::notice('games created.', [
            'league-id' => $league->id,
            'size' => $league->league_size->size,
            'iterations' => $iterations,
            'games-per-day' => $g_perday,
            'games-count' => $league->schedule->events->count(), ]);
    }

    /**
     * inject games for a new team into a leagues iwth eixsting games
     *
     * @param  League  $league
     * @param  Team  $team
     * @param  int  $league_no
     * @return void
     */
    public function inject_team_games(League $league, Team $team, int $league_no): void
    {
        if (! $league->is_custom) {
            Log::info('injecting games for a single team.', ['league-id' => $league->id, 'team-id' => $team->id]);
            // get size
            $league->load('schedule');
            // get scheme
            $scheme = $league->schedule->schemes()->get();

            // get schedule
            $gdate_by_day = $league->schedule->events()->orderBy('game_day')->pluck('game_date', 'game_day');
            $weekend_by_day = $league->schedule->events()->orderBy('game_day')->pluck('full_weekend', 'game_day');
            $iterations = $league->schedule->iterations ?? 1;
            $max_gday = $scheme->max('game_day') ?? 1;
            $g_perday = ($league->league_size->size / 2) ?? 1;

            // get teams
            $teams = $league->teams()->with('club', 'gym')->get();

            if ($league->games()->exists()) {
                for ($i = 0; $i < $iterations; $i++) {
                    foreach ($scheme as $s) {
                        $i_game_no = $s->game_no + ($max_gday * $i * $g_perday);
                        $i_game_day = $s->game_day + ($max_gday * $i);

                        if (($s->team_home == $league_no) or ($s->team_guest == $league_no)) {
                            $gdate = $gdate_by_day[$i_game_day] ?? 'notset';
                            if ($gdate != 'notset') {
                                $gday = CarbonImmutable::parse($gdate);
                                $full_weekend = $weekend_by_day[$i_game_day];

                                if (! $league->games()->where('game_no', $i_game_no)->exists()) {
                                    $hteam = $teams->firstWhere('league_no', $s->team_home);
                                    $gteam = $teams->firstWhere('league_no', $s->team_guest);

                                    if ( (isset($hteam)) and
                                         (isset($gteam)) ) {
                                        $g = [];
                                        $g['league_id'] = $league->id;
                                        $g['game_no'] = $i_game_no;
                                        $g['region'] = $league->region->code;
                                        $g['game_plandate'] = $gday;

                                        if ($full_weekend) {
                                            if (isset($hteam['preferred_game_day'])) {
                                                $pref_gday = $hteam['preferred_game_day'] % 7;
                                                $g['game_date'] = $gday->subDay(3)->next($pref_gday);
                                            } else {
                                                $g['game_date'] = $gday;
                                            }
                                        } else {
                                            $g['game_date'] = $gday;
                                        }

                                        if ($league->age_type->in([LeagueAgeType::Junior(), LeagueAgeType::Mini()])) {
                                            $g['referee_1'] = '****';
                                        }

                                        $g['team_char_home'] = $s->team_home;
                                        $g['team_char_guest'] = $s->team_guest;

                                        if (isset($hteam)) {
                                            $g['game_time'] = $hteam['preferred_game_time'];
                                            $g['gym_id'] = $hteam->gym()->exists() ? $hteam->gym->id : $hteam->club->gyms()->first()->id;
                                            $g['club_id_home'] = $hteam['club']['id'];
                                            $g['team_id_home'] = $hteam['id'];
                                        }

                                        if (isset($gteam)) {
                                            $g['club_id_guest'] = $gteam['club']['id'];
                                            $g['team_id_guest'] = $gteam['id'];
                                        }

                                        Log::debug('creating game no.', ['game-no' => $g['game_no']]);
                                        Game::create($g);
                                    } else {
                                        Log::notice('[GAME GENERATION] home or guest missing', ['league-id' => $league->id, 'game-no' => $s->game_no]);
                                    }
                                } else {
                                    $game = $league->games()->where('game_no', $i_game_no)->where('team_char_home', $league_no)->first();
                                    if (isset($game)) {
                                        $game->club_id_home = $team->club->id;
                                        $game->region_id_home = $team->club->region->id;
                                        $game->team_id_home = $team->id;
                                        $game->game_time = $team->preferred_game_time;

                                        if ($full_weekend) {
                                            if (isset($team['preferred_game_day'])) {
                                                $pref_gday = $team['preferred_game_day'] % 7;
                                                $game->game_date = $game->game_date->subDay(3)->next($pref_gday);
                                            } else {
                                                $game->game_date = $gday;
                                            }
                                        } else {
                                            $game->game_date = $gday;
                                        }

                                        $team->load('gym');
                                        $game->gym_id = $team->gym()->exists() ? $team->gym->id : $team->club->gyms()->first()->id;
                                        $game->save();
                                        Log::debug('updating game no.', ['game-no' => $game->game_no]);
                                    }

                                    $league->games()->where('game_no', $i_game_no)->where('team_char_guest', $league_no)->update([
                                        'club_id_guest' => $team->club->id,
                                        'region_id_guest' => $team->club->region->id,
                                        'team_id_guest' => $team->id,
                                    ]);
                                }
                            } else {
                                Log::warning('[GAME GENERATION] Gameday not set', ['league-id' => $league->id, 'game-day' => $s->game_day]);
                            }
                        }
                    }
                }
            }
        } else {
            Log::warning('cannot inject games for the new team. this is a league with custom schedule.', ['league-id' => $league->id, 'team-id' => $team->id]);
        }
    }

    /**
     * reset games for a league if a team is withdrawn
     *
     * @param  League  $league
     * @param  Team  $team
     * @return void
     */
    public function blank_team_games(League $league, Team $team): void
    {
        // delete home and guest games
        $count = $league->games()->where('team_id_home', $team->id)->orWhere('team_id_guest', $team->id)->delete();
        Log::notice('games for team deleted', ['league-id' => $league->id, 'team-id' => $team->id, 'count'=>$count]);
    }


}
