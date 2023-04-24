<?php

namespace App\Traits;

use App\Models\League;
use App\Models\Team;
use App\Models\User;
use Bouncer as Bouncer;
use Illuminate\Support\Str;

trait LeagueTeamManager
{
    use LeagueFSM;
    protected function getNextFreeSlot(League $league): array
    {
        // get all assigned clubs
        $used_nos = $league->clubs->where('pivot.league_no', '<=', $league->size)->pluck('pivot.league_no');

        // get max possible league_nos
        $max_nos = collect([1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16])->slice(0, $league->size);

        // get the remaining ones
        $left_no = $max_nos->diff($used_nos)->first();
        return [$left_no, config('dunkomatic.league_team_chars')[$left_no]];
    }

    protected function get_registrations(League $league): array
    {
        // get all assigned clubs, registerd teams and free league numbers

        $clubteam = collect();
        $c_keys = collect(range(1, $league->size));
        $t_keys = collect(range(1, $league->size));

        $clubs = $league->clubs()->with(['region'])->get()->sortBy('pivot.league_no');
        foreach ($clubs as $c) {
            $clubteam[] = [
                'club_shortname' => $c->shortname,
                'club_league_no' => $c->pivot->league_no ?? null,
                'club_id' => $c->id,
                'team_id' => null,
                'team_name' => null,
                'team_league_no' => null,
                'team_league_char' => null,
                'team_no' => null,
                'region_code' => $c->region->code,
            ];
            if ($c->pivot->league_no != null) {
                $c_keys->pull($c->pivot->league_no - 1);
            }
        }
        $teams = $league->teams()->with(['club', 'club.region'])->get();

        $clubteam->transform(function ($item) use (&$teams, &$t_keys) {
            $k = $teams->search(function ($t) use ($item) {
                return ($t['club_id'] == $item['club_id']) and ($item['team_id'] == null);
            });
            if ($k !== false) {
                $item['team_id'] = $teams[$k]->id;
                $item['team_name'] = $teams[$k]->name;
                $item['team_league_no'] = $teams[$k]->league_no;
                $item['team_league_char'] = $teams[$k]->league_char;
                $item['team_no'] = $teams[$k]->team_no;

                if ($teams[$k]->league_no != null) {
                    $t_keys->pull($teams[$k]->league_no - 1);
                }

                $teams->pull($k);
            }

            return $item;
        });

        foreach ($teams as $t) {
            $clubteam[] = [
                'club_shortname' => null,
                'club_league_no' => null,
                'club_id' => null,
                'team_id' => $t->id,
                'team_name' => $t->name,
                'team_league_no' => $t->league_no,
                'team_league_char' => $t->league_char,
                'team_no' => $t->team_no,
                'region_code' => null,
            ];
            if ($t->league_no != null) {
                $t_keys->pull($t->league_no - 1);
            }
        }

        for ($i = count($clubteam); $i < ($league->size); $i++) {
            $clubteam[] = [
                'club_shortname' => null,
                'club_league_no' => null,
                'club_id' => null,
                'team_id' => null,
                'team_name' => null,
                'team_league_no' => null,
                'team_league_char' => null,
                'team_no' => null,
                'region_code' => null,
            ];
        }

        return [$clubteam, $c_keys, $t_keys];
    }

    protected function get_button_settings(League $league, User $user, $club_id, $team_id, $club_league_no, $team_league_no, $club_name, $team_name): array
    {
        $status = 'disabled'; // default is disabled
        $function = '';
        $color = 'btn-light';
        $scolor = 'btn-light';
        $text = '';

        // handle color and text
        if ($team_league_no != null) {
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
        if ((Bouncer::can('access', $league->region) and Bouncer::is($user)->a('regionadmin')) or
            (Bouncer::is($user)->an('superadmin')) or
            (Bouncer::can('access', $league) and Bouncer::can('access', $league->region) and Bouncer::is($user)->a('leagueadmin'))) {
            if ($this->can_register_teams($league)) {
                $status = '';
                if ($club_id == null) {
                    $function = 'assignClub';
                    $scolor = 'btn-primary';
                    $text = Str::limit(__('league.action.assign'), 6, '...');
                } else {
                    if ($team_id == null) {
                        $function = 'registerTeam#deassignClub';
                        $scolor = 'btn-warning#btn-light';
                    } else {
                        if (! $league->load('schedule', 'league_size')->is_custom) {
                            if ($team_league_no == null) {
                                $function = 'pickChar#unregisterTeam';
                                $scolor = 'btn-success#btn-primary';
                            } else {
                                $function = 'releaseChar';
                                $scolor = 'btn-warning';
                            }
                        } else {
                            $function = 'unregisterTeam';
                            $scolor = 'btn-primary';
                        }
                    }
                }
            } elseif ($this->can_withdraw_teams($league)) {
                if ($team_id != null) {
                        $status = '';
                        $function = 'withdrawTeam';
                        $scolor = 'btn-primary';
                }
            }
        }

        return [$status, $color, $text, $function, $scolor];
    }

    protected function get_custom_league_league_no(League $league, Team $team)
    {
        $chars = config('dunkomatic.league_team_chars');
        $all_nos = collect(array_slice($chars, 0, $league->size, true));
        $used_nos = $league->teams->pluck('league_no', 'league_no');
        $free_nos = $all_nos->diffKeys($used_nos);

        $league_no = $league->clubs()->where('club_id', $team->club->id)->first()->pivot->league_no;
        if (($league_no > $league->size) or ($used_nos->keys()->contains($league_no))) {
            $league_no = $free_nos->keys()->first();
        }
        $league_char = $all_nos[$league_no];

        return [$league_no, $league_char];
    }
}
