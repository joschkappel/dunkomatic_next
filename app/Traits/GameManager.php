<?php

namespace App\Traits;

use App\Enums\LeagueAgeType;
use App\Models\League;
use App\Models\Team;
use App\Models\Game;
use App\Models\Club;

use Illuminate\Support\Facades\Log;


trait GameManager {
    public function create_games(League $league) {
        // get size
        $league->load('schedule');
        // get scheme

        if ( $league->schedule->custom_events) {
          $scheme = $league->league_size->schemes()->get();
          $gdate_by_day = collect([]);
        } else {
          $scheme = $league->schedule->schemes()->get();
        // get game days and dates
          $gdate_by_day = $league->schedule->events()->pluck('game_date','game_day');
        }

        // get teams
        $teams = $league->teams()->with('club')->get();

        foreach ($scheme as $s){
          $gday = $gdate_by_day[ $s->game_day ] ?? now();

          $hteam = $teams->firstWhere('league_no', $s->team_home);
          $gteam = $teams->firstWhere('league_no', $s->team_guest);

          $g = array();
          $g['region'] = $league->region->code;
          $g['game_plandate'] = $gday;
          if (isset($hteam['preferred_game_day'])){
            $pref_gday = $hteam['preferred_game_day'] % 7;
            $g['game_date'] = $gday->next($pref_gday);
          } else {
            $g['game_date'] = $gday;
          };

          if ($league->age_type->in( [LeagueAgeType::Junior(), LeagueAgeType::Mini()] ) ){
            $g['referee_1'] = "****";
          } else {
            $g['referee_1'] = "";
          }

          $g['referee_2'] = "";
          $g['team_char_home'] = $s->team_home;
          $g['team_char_guest'] = $s->team_guest;

          if (isset($hteam)){
            $g['game_time'] = $hteam['preferred_game_time'];
            $g['gym_no'] = Club::find($hteam['club']['id'])->gyms()->first()->gym_no;
            $g['gym_id'] = Club::find($hteam['club']['id'])->gyms()->first()->id;
            $g['club_id_home'] = $hteam['club']['id'];
            $g['team_id_home'] = $hteam['id'];
            $g['team_home'] = $hteam['club']['shortname'].$hteam['team_no'];
          };

          if ( isset($gteam)){
            $g['club_id_guest'] = $gteam['club']['id'];
            $g['team_id_guest'] = $gteam['id'];
            $g['team_guest'] = $gteam['club']['shortname'].$gteam['team_no'];
          }

          //Log::debug(print_r($g, true));
          Game::updateOrCreate(['league_id' => $league->id, 'game_no' => $s->game_no], $g);
        }
    }

    public function inject_team_games(League $league, Team $team, $league_no) {
        // get size
        $league->load('schedule');
        // get scheme
        $scheme = $league->schedule->schemes()->get();

        // get schedule
        $gdate_by_day = $league->schedule->events()->pluck('game_date','game_day');

        // get teams
        $teams = $league->teams()->with('club')->get();


        if ($league->games()->exists()){
          foreach ($scheme as $s){
            if (($s->team_home == $league_no) or ($s->team_guest == $league_no)){

              if (!$league->games()->where('game_no',$s->game_no)->exists()) {

                $gday = $gdate_by_day[ $s->game_day ];
                $hteam = $teams->firstWhere('league_no', $s->team_home);
                $gteam = $teams->firstWhere('league_no', $s->team_guest);

                $g = array();
                $g['league_id'] = $league->id;
                $g['game_no'] = $s->game_no;
                $g['region'] = $league->region->code;
                $g['game_plandate'] = $gday;
                if (isset($hteam['preferred_game_day'])){
                  $pref_gday = $hteam['preferred_game_day'] % 7;
                  $g['game_date'] = $gday->next($pref_gday);
                } else {
                  $g['game_date'] = $gday;
                };
                $g['gym_no'] = "1";

                if ($league->age_type->in( [ LeagueAgeType::Junior(), LeagueAgeType::Mini() ] ) ){
                  $g['referee_1'] = "****";
                } else {
                  $g['referee_1'] = "";
                }

                $g['referee_2'] = "";
                $g['team_char_home'] = $s->team_home;
                $g['team_char_guest'] = $s->team_guest;

                if (isset($hteam)){
                  $g['game_time'] = $hteam['preferred_game_time'];
                  $g['club_id_home'] = $hteam['club']['id'];
                  $g['team_id_home'] = $hteam['id'];
                  $g['team_home'] = $hteam['club']['shortname'].$hteam['team_no'];
                };

                if ( isset($gteam)){
                  $g['club_id_guest'] = $gteam['club']['id'];
                  $g['team_id_guest'] = $gteam['id'];
                  $g['team_guest'] = $gteam['club']['shortname'].$gteam['team_no'];
                }

                Log::debug('creating game no:'.$g['game_no']);
                Game::create($g);
              } else {
                $game = $league->games()->where('game_no',$s->game_no)->where('team_char_home',$league_no)->first();
                if (isset($game)){
                  $game->club_id_home = $team->club->id;
                  $game->team_id_home = $team->id;
                  $game->team_home = $team->club->shortname.$team->team_no;
                  $game->game_time = $team->preferred_game_time;
                  if (isset($team['preferred_game_day'])){
                      $pref_gday = $team['preferred_game_day'] % 7;
                      $game->game_date = $game->game_date->next($pref_gday);
                  }
                  $game->save();
                }

                $league->games()->where('game_no',$s->game_no)->where('team_char_guest',$league_no)->update([
                    'club_id_guest'=>$team->club->id,
                    'team_id_guest'=>$team->id,
                    'team_guest'=>$team->club->shortname.$team->team_no ]);
              }
            }
          }
        }
    }

    public function blank_team_games(League $league, Team $team){
        // blank out home games
        $league->games()->where('team_id_home', $team->id)->update([
            'team_id_home'=>null,
            'club_id_home'=>null,
            'team_home'=>null,
            'gym_no'=>null,
            'gym_id'=>null
        ]);

        // blank out guest games
        $league->games()->where('team_id_guest', $team->id)->update([
            'team_id_guest'=>null,
            'club_id_guest'=>null,
            'team_guest'=>null,
        ]);

    }


}
