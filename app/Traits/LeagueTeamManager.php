<?php

namespace App\Traits;

use App\Enums\LeagueState;
use App\Models\League;

use App\Models\User;
use Bouncer as Bouncer;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

trait LeagueTeamManager
{

    protected function get_registrations (League $league): array
    {
        // get all assigned clubs, registerd teams and free league numbers

        $clubteam = collect();
        $c_keys = collect(range(1, $league->size));
        $t_keys = collect(range(1, $league->size));

        $clubs = $league->clubs()->get()->sortBy('pivot.league_no');
        foreach ($clubs as $c) {
            $clubteam[] = array(
                'club_shortname' => $c->shortname,
                'club_league_no' => $c->pivot->league_no ?? null,
                'club_id' => $c->id,
                'team_id' => null,
                'team_name' => null,
                'team_league_no' => null,
                'team_league_char' => null,
                'team_no' => null,
                'region_code' => $c->region->code
            );
            if ($c->pivot->league_no != null) {
                $c_keys->pull($c->pivot->league_no - 1);
            };
        }
        $teams = $league->teams;

        $clubteam->transform(function ($item) use (&$teams, &$t_keys) {
            $k = $teams->search(function ($t) use ($item) {
                return (($t['club_id'] == $item['club_id']) and ($item['team_id'] == null));
            });
            if ($k !== false) {
                $item['team_id'] = $teams[$k]->id;
                $item['team_name'] = $teams[$k]->name;
                $item['team_league_no'] = $teams[$k]->league_no;
                $item['team_league_char'] = $teams[$k]->league_char;
                $item['team_no'] = $teams[$k]->team_no;

                if ($teams[$k]->league_no != null) {
                    $t_keys->pull($teams[$k]->league_no - 1);
                };

                $teams->pull($k);
            }
            return $item;
        });

        foreach ($teams as $t) {
            $clubteam[] = array(
                'club_shortname' => null,
                'club_league_no' => null,
                'club_id' => null,
                'team_id' => $t->id,
                'team_name' => $t->name,
                'team_league_no' => $t->league_no,
                'team_league_char' => $t->league_char,
                'team_no' => $t->team_no,
                'region_code' => null
            );
            if ($t->league_no != null) {
                $t_keys->pull($t->league_no - 1);
            };
        }


        for ($i = count($clubteam); $i < ($league->size); $i++) {
            $clubteam[] = array(
                'club_shortname' => null,
                'club_league_no' => null,
                'club_id' => null,
                'team_id' => null,
                'team_name' => null,
                'team_league_no' => null,
                'team_league_char' => null,
                'team_no' => null,
                'region_code' => null
            );
        }

        return array($clubteam, $c_keys, $t_keys);
    }

    protected function get_button_settings(League $league, User $user,  $club_id, $team_id, $club_league_no, $team_league_no, $club_name, $team_name): array
    {
        $status = 'disabled'; // default is disabled
        $function = '';
        $color = 'btn-light';
        $scolor = 'btn-light';
        $text = '';

        // handle color and text
        if ($team_league_no != null ){
            $color = 'btn-success';
            $text = $team_name;
        } else {
            if ($team_id != null) {
                $color = 'btn-warning';
                $text = $team_name;
            } else {
                if ($club_id != null) {
                    $color = 'btn-primary';
                    $text = $club_name;
                }
            }
        }

        // handle disabled / enabled button status and function
        if (( Bouncer::can('access', $league->region)  and Bouncer::is($user)->a('regionadmin') ) or
            ( Bouncer::is($user)->an('superadmin') ) or
            ( Bouncer::can('access', $league ) and Bouncer::is($user)->a('leagueadmin')) ){

            if ( $league->state->in([LeagueState::Registration, LeagueState::Selection()]) ){
                $status = '';
                if ($club_id == null){
                    $function = 'assignClub';
                    $scolor = 'btn-primary';
                    $text = Str::limit(__('league.action.assign'), 6, '...');
                } else {
                    if ($team_id == null){
                        $function = 'registerTeam#deassignClub';
                        $scolor = 'btn-warning#btn-light';
                    } else {
                        if ($team_league_no ==  null){
                            $function = 'pickChar#unregisterTeam';
                            $scolor = 'btn-success#btn-primary';
                        } else {
                            $function = 'releaseChar';
                            $scolor = 'btn-warning';
                        }
                    }
                }
            }
        }

        return array($status, $color, $text, $function, $scolor);
    }
}
