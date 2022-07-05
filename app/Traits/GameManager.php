<?php

namespace App\Traits;

use App\Enums\LeagueAgeType;
use App\Models\League;
use App\Models\Team;
use App\Models\Game;
use App\Models\Club;

use Carbon\CarbonImmutable;

use Illuminate\Support\Facades\Log;


trait GameManager
{
    /**
     * deletes all games for a league in the DB
     *
     * @param League $league
     * @return void
     *
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
     * @param League $league
     * @return void
     *
     */
    public function create_games(League $league): void
    {
        Log::info('creating games for league.', ['league-id' => $league->id]);
        // get size
        $league->load('schedule','league_size');
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
        $teams = $league->teams()->with('club')->get();

        for ($i=0; $i < $iterations ; $i++) {
            foreach ($scheme as $s) {
                $gday = CarbonImmutable::parse($gdate_by_day[$s->game_day + ( $max_gday * $i)]) ?? 'notset';
                $full_weekend = $weekend_by_day[$s->game_day + ( $max_gday * $i)];

                if ($gday != 'notset'){
                    Log::debug('[GAME GENERATION] working on game day',['league-id'=>$league->id, 'game-day'=>$s->game_day ]);
                    $hteam = $teams->firstWhere('league_no', $s->team_home);
                    $gteam = $teams->firstWhere('league_no', $s->team_guest);

                    $g = array();
                    $g['region'] = $league->region->code;
                    $g['game_plandate'] = $gday;

                    if ($full_weekend){
                        if (isset($hteam['preferred_game_day'])) {
                            $pref_gday = $hteam['preferred_game_day'] % 7;
                            $g['game_date'] = $gday->subDay(2)->next($pref_gday);
                        } else {
                            $g['game_date'] = $gday;
                        };
                    } else {
                        $g['game_date'] = $gday;
                    }



                    if ($league->age_type->in([LeagueAgeType::Junior(), LeagueAgeType::Mini()])) {
                        $g['referee_1'] = "****";
                    }

                    $g['team_char_home'] = $s->team_home;
                    $g['team_char_guest'] = $s->team_guest;

                    if (isset($hteam)) {
                        $g['game_time'] = $hteam['preferred_game_time'];
                        $g['gym_no'] = Club::find($hteam['club']['id'])->gyms()->first()->gym_no ?? null;
                        $g['gym_id'] = Club::find($hteam['club']['id'])->gyms()->first()->id ?? null;
                        $g['club_id_home'] = $hteam['club']['id'];
                        $g['team_id_home'] = $hteam['id'];
                        $g['team_home'] = $hteam['club']['shortname'] . $hteam['team_no'];
                    };

                    if (isset($gteam)) {
                        $g['club_id_guest'] = $gteam['club']['id'];
                        $g['team_id_guest'] = $gteam['id'];
                        $g['team_guest'] = $gteam['club']['shortname'] . $gteam['team_no'];
                    }

                    //Log::debug(print_r($g, true));
                    Game::updateOrCreate(['league_id' => $league->id, 'game_no' => $s->game_no + ( $max_gday * $i * $g_perday) ], $g);
                } else {
                    Log::warning('[GAME GENERATION] Gameday not set',['league-id'=>$league->id, 'game-day'=>$s->game_day ]);
                }
            }
        }
        Log::notice('games created.', [
            'league-id'=>$league->id,
            'size'=>$league->league_size->size,
            'iterations'=>$iterations,
            'games-per-day'=>$g_perday,
            'games-count'=> count($scheme)*$iterations]);
    }

    /**
     * inject games for a new team into a leagues iwth eixsting games
     *
     * @param League $league
     * @param Team $team
     * @param int $league_no
     * @return void
     */
    public function inject_team_games(League $league, Team $team, int $league_no): void
    {
        if (! $league->is_custom){
            Log::info('injecting games for a single team.', ['league-id' => $league->id, 'team-id'=>$team->id]);
            // get size
            $league->load('schedule');
            // get scheme
            $scheme = $league->schedule->schemes()->get();

            // get schedule
            $gdate_by_day = $league->schedule->events()->pluck('game_date', 'game_day');
            $iterations = $league->schedule->iterations ?? 1;
            $max_gday = $scheme->max('game_day') ?? 1;
            $g_perday = ($league->league_size->size / 2) ?? 1;

            // get teams
            $teams = $league->teams()->with('club')->get();


            if ($league->games()->exists()) {
                for ($i=0; $i < $iterations ; $i++) {
                    foreach ($scheme as $s) {
                        $i_game_no = $s->game_no + ( $max_gday * $i * $g_perday);
                        $i_game_day = $s->game_day + ( $max_gday * $i);

                        if (($s->team_home == $league_no) or ($s->team_guest == $league_no)) {
                            if (!$league->games()->where('game_no', $i_game_no )->exists()) {

                                $gday = $gdate_by_day[$i_game_day];
                                $hteam = $teams->firstWhere('league_no', $s->team_home);
                                $gteam = $teams->firstWhere('league_no', $s->team_guest);

                                $g = array();
                                $g['league_id'] = $league->id;
                                $g['game_no'] = $i_game_no;
                                $g['region'] = $league->region->code;
                                $g['game_plandate'] = $gday;
                                if (isset($hteam['preferred_game_day'])) {
                                    $pref_gday = $hteam['preferred_game_day'] % 7;
                                    $g['game_date'] = $gday->subDay(1)->next($pref_gday);
                                } else {
                                    $g['game_date'] = $gday;
                                };
                                $g['gym_no'] = $hteam->club->gyms->first()->gym_no;
                                $g['gym_id'] = $hteam->club->gyms->first()->id;

                                if ($league->age_type->in([LeagueAgeType::Junior(), LeagueAgeType::Mini()])) {
                                    $g['referee_1'] = "****";
                                }

                                $g['team_char_home'] = $s->team_home;
                                $g['team_char_guest'] = $s->team_guest;

                                if (isset($hteam)) {
                                    $g['game_time'] = $hteam['preferred_game_time'];
                                    $g['club_id_home'] = $hteam['club']['id'];
                                    $g['team_id_home'] = $hteam['id'];
                                    $g['team_home'] = $hteam['club']['shortname'] . $hteam['team_no'];
                                };

                                if (isset($gteam)) {
                                    $g['club_id_guest'] = $gteam['club']['id'];
                                    $g['team_id_guest'] = $gteam['id'];
                                    $g['team_guest'] = $gteam['club']['shortname'] . $gteam['team_no'];
                                }

                                Log::debug('creating game no.', ['game-no' => $g['game_no']]);
                                Game::create($g);
                            } else {
                                $game = $league->games()->where('game_no', $i_game_no)->where('team_char_home', $league_no)->first();
                                if (isset($game)) {
                                    $game->club_id_home = $team->club->id;
                                    $game->team_id_home = $team->id;
                                    $game->team_home = $team->name;
                                    $game->game_time = $team->preferred_game_time;
                                    if (isset($team['preferred_game_day'])) {
                                        $pref_gday = $team['preferred_game_day'] % 7;
                                        $game->game_date = $game->game_date->subDay(1)->next($pref_gday);
                                    }
                                    $game->gym_no  = $team->club->gyms->first()->gym_no;
                                    $game->gym_id = $team->club->gyms->first()->id;
                                    $game->save();
                                    Log::debug('updating game no.', ['game-no' => $game->game_no]);
                                }

                                $league->games()->where('game_no', $i_game_no)->where('team_char_guest', $league_no)->update([
                                    'club_id_guest' => $team->club->id,
                                    'team_id_guest' => $team->id,
                                    'team_guest' => $team->club->shortname . $team->team_no
                                ]);
                            }
                        }
                    }
                }
            }
        } else {
            Log::notice('cannot inject games for the new team. this is a league with custom schedule.', ['league-id' => $league->id, 'team-id'=>$team->id]);
        }
    }

    /**
     * reset games for a league if a team is withdrawn
     *
     * @param League $league
     * @param Team $team
     * @return void
     */
    public function blank_team_games(League $league, Team $team): void
    {
        Log::info('removing team from games.', ['league-id'=>$league->id, 'team-id'=>$team->id]);
        // blank out home games
        $league->games()->where('team_id_home', $team->id)->update([
            'team_id_home' => null,
            'club_id_home' => null,
            'team_home' => null,
            'gym_no' => null,
            'gym_id' => null
        ]);

        // blank out guest games
        $league->games()->where('team_id_guest', $team->id)->update([
            'team_id_guest' => null,
            'club_id_guest' => null,
            'team_guest' => null,
        ]);
    }
}
