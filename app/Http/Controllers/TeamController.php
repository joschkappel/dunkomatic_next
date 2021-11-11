<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\Club;
use App\Models\League;
use App\Models\Game;
use App\Traits\GameManager;
use App\Enums\LeagueState;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;

class TeamController extends Controller
{
    use GameManager;

    public function sb_league(League $league)
    {
        $teams =  $league->teams()->with('club')->get();
        Log::info('preparing select2 teams list for league.', ['league-id'=>$league->id, 'count'=>count($teams)]);

        $response = array();

        foreach ($teams as $t) {
            $response[] = array(
                "id" => $t->id,
                "text" => $t['club']->shortname . $t->team_no
            );
        }
        return Response::json($response);
    }

    public function withdraw(Request $request, League $league)
    {
        $data = $request->validate([
            'team_id' => 'required|exists:teams,id',
        ]);
        Log::info('team withdrawal form data validated OK.');

        $team = Team::findOrFail($data['team_id']);

        // Team: league_prev, league_id, league_char, league_no,
        $team->update(['league_prev' => $league->shortname, 'league_id' => null, 'league_char' => null, 'league_no' => null]);
        Log::info('team deregistered from league',['team-id'=>$team->id, 'league-id'=>$league->id]);

        // Game: blank all games with gameteam home+guest
        $this->blank_team_games($league, $team);

        // League:club delete
        $upperArr = config('dunkomatic.league_team_chars');
        // special treatment as values might be duplicate
        $occurences = $league->clubs->pluck('id')->intersect([$team->club->id])->count();
        if ($occurences > 1) {
            Log::info('teams club has multiple assignments', ['league-id'=>$league->id, 'club-id'=>$team->club->id, 'assignments'=>$occurences]);
            $assigned_clubs = $league->clubs->pluck('id')->diff([$team->club->id]);
            for ($i = 1; $i < $occurences; $i++) {
                $assigned_clubs[] = $team->club->id;
            }
            $league->clubs()->detach();
            foreach ($assigned_clubs as $i => $ac) {
                $c = $upperArr[$i + 1];
                $league->clubs()->attach([$ac => ['league_no' => $i + 1, 'league_char' => $c]]);
            }
        } else {
            $league->clubs()->detach($team->club);
            Log::info('club deassigned from league', ['league-id'=>$league->id, 'club-id'=>$team->club->id]);
        }

        return redirect()->back();
    }

    public function sb_freeteam(League $league)
    {
        $region = $league->region;
        //Log::debug(print_r($league,true));
        if ($region->is_top_level) {
            Log::notice('getting unregistered teams for top level region');
            $free_teams = collect();
            foreach ($region->childRegions as $r) {
                $t = $r->teams()->whereNull('league_id')->orWhere(function ($query) use ($league) {
                    $query->where('league_id', $league->id)
                        ->whereNull('league_no');
                })->with('club')->get();
                $free_teams = $free_teams->concat($t);
            }
        } else {
            Log::notice('getting unregistered teams for base level region');
            $free_teams = $region->teams()->whereNull('league_id')->orWhere(function ($query) use ($league) {
                $query->where('league_id', $league->id)
                    ->whereNull('league_no');
            })->with('club')->get();
        }

        $response = array();
        Log::info('preparing select2 unregistered team list', ['league'=>$league->id, 'count' => count($free_teams)] );

        foreach ($free_teams as $t) {
            if ($region->is_top_level) {
                $response[] = array(
                    "id" => $t->id,
                    "text" => $t->club->region->code . ' : ' . $t['club']->shortname . $t->team_no . ' (' . $t->league_prev . ')'
                );
            } else {
                $response[] = array(
                    "id" => $t->id,
                    "text" => $t['club']->shortname . $t->team_no . ' (' . $t->league_prev . ')'
                );
            }
        }
        return Response::json($response);
    }

    public function inject(Request $request, League $league)
    {
        $data = $request->validate([
            'league_no' => 'required|integer|between:1,16',
            'team_id' => 'required|exists:teams,id'
        ]);
        Log::info('team inject form data validated OK.');

        $league_no = $data['league_no'];
        $size = $league->size;
        $chars = config('dunkomatic.league_team_chars');
        $upperArr = array_slice($chars, 0, $size, true);
        $league_char = $upperArr[$league_no];
        // update team
        $team = Team::findOrFail($data['team_id']);
        $team->update(['league_id' => $league->id, 'league_no' => $league_no, 'league_char' => $league_char]);
        Log::notice('team added to league.', ['league-id'=>$league->id, 'team-id'=>$team->id]);

        $used_char = $league->clubs->pluck('pivot.league_char')->toArray();
        $free_char = array_diff($upperArr, $used_char);

        // special treatment as values might be duplicate
        $occurences_club = $league->clubs->pluck('id')->intersect([$team->club->id])->count();
        $occurences_team = $league->teams->pluck('club_id')->intersect([$team->club->id])->count();

        if ($league->clubs->count() < $size) {
            if ($occurences_club < $occurences_team) {
                $clubleague_char = array_shift($free_char);
                $clubleague_no = array_search($clubleague_char, $chars, false);
                $league->clubs()->attach($team->club->id, ['league_no' => $clubleague_no, 'league_char' => $clubleague_char]);
                Log::info('club assigned to league.', ['league-id'=>$league->id, 'club-id'=>$team->club->id]);
            }
        }

        $this->inject_team_games($league, $team, $league_no);

        return redirect()->back();
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store_plan(Request $request)
    {
        $upperArr = config('dunkomatic.league_team_chars');

        foreach ($request->input() as $key => $league_no) {
            if (strpos($key, 'sel') !== false) {
                $team_id = explode(':', $key)[2];

                // update team league character
                $team = Team::findOrFail($team_id);
                $team->preferred_league_no = $league_no;
                $team->preferred_league_char = $upperArr[$league_no];

                $check = $team->save();
                Log::notice('team league char set', ['team-id'=>$team->id, 'league-team-no'=> $league_no]);
            }
        }

        return Response::json($check);
    }


    /**
     * Attach team to league
     *
     * @param  \App\Models\League  $league
     * @return \Illuminate\Http\Response
     */
    public function assign_league(Request $request)
    {
        // get data
        $data = $request->validate([
            'team_id' => 'required|exists:teams,id',
            'league_id' => 'required|exists:leagues,id',
        ]);
        Log::info('team registration form data validated OK.');

        $team = Team::findOrFail($data['team_id']);
        $league = League::findOrFail($data['league_id']);

        $team->league()->associate($league);
        $team->save();
        Log::notice('team registered for league.', ['team-id'=>$team->id, 'league-id'=>$league->id]);

        return redirect()->route('club.dashboard', ['language' => app()->getLocale(), 'club' => $team->club->id]);
    }

    /**
     * Attach team to league
     *
     * @param  \App\Models\League  $league
     * @return \Illuminate\Http\Response
     */
    public function pick_char(Request $request, League $league)
    {
        // get data
        $data = $request->validate([
            'team_id' => 'required|exists:teams,id',
            'league_no' => 'required|integer|between:1,16',
        ]);
        Log::info('team league no form data validated OK.');

        $udata = array();
        $udata['league_id'] = $league->id;
        $udata['league_no'] = $data['league_no'];
        $upperArr = config('dunkomatic.league_team_chars');
        $udata['league_char'] = $upperArr[$data['league_no']];

        $team = Team::findOrFail($data['team_id']);
        $team->update($udata);
        Log::notice('team registered and league no set.', ['team-id'=>$team->id , 'league-id'=>$league->id , 'league-team-no'=>$data['league_no']]);

        return Response::json(['success' => 'all good'], 200);
    }


    /**
     * DeAttach team from league
     *
     * @param  \App\Models\League  $league
     * @return \Illuminate\Http\Response
     */
    public function deassign_league(Request $request)
    {
        // get data
        $data = $request->validate([
            'team_id' => 'required|exists:teams,id',
            'league_id' => 'required|exists:leagues,id',
        ]);
        Log::info('team unregistration form data validated OK.');

        $team = Team::findOrFail($data['team_id']);
        $league = League::findOrFail($data['league_id']);

        $team->update(['league_id' => null, 'league_no' => null, 'league_char' => null]);
        Log::notice('team un-registered and league no cleared.', ['team-id'=>$team->id , 'league-id'=>$league->id , 'league-team-no'=>$team->league_no]);

        $league->clubs()->wherePivot('club_id', '=', $team->club->id)->detach();
        Log::info('club deassigned from league.', ['club-id'=>$team->club->id , 'league-id'=>$league->id]);

        if ($league->state > LeagueState::Freeze()) {
            Game::whereIn('id', $team->games_guest->pluck('id'))->delete();
            Game::whereIn('id', $team->games_home->pluck('id'))->delete();
            Log::info('games deleted for team.', ['team-id'=>$team->id , 'league-id'=>$league->id]);
        }

        return Response::json(true);
    }

    /**
     * Display a dashboard
     *
     * @return \Illuminate\Http\Response
     */
    public function plan_leagues($language, Club $club)
    {
        Log::info('show league planning dashboard.', ['club-id'=>$club->id]);
        $data['club'] =  $club;
        $teams = $data['club']->teams()->whereNotNull('league_id')->with('league')->get();
        $data['teams'] = $teams->where('league.size', '>', 0)->sortBy('league.schedule_id');

        return view('team/teamleague_dashboard', $data);
    }

    public function list_pivot(Request $request)
    {

        Log::info('preparing team league pivot table.');

        $select = "select date_format(se.game_date, '%a %d.%b.%Y') as 'Game Date' ";
        $where =  array();
        $cols = array();

        foreach ($request->all() as $key => $league_no) {
            if (strpos($key, 'sel') !== false) {
                $league = explode(':', $key)[1];

                $cols[] = 'max(case when (l.id = ' . $league . ' and lts.team_home = ' . $league_no . ') then se.game_day else " " end) as "l' . $league . '"';
                $where[] = $league;
            }
        }

        $select .= ', ' . implode(' ,', $cols) . ' FROM league_size_schemes lts, schedule_events se  , leagues l , schedules s ';
        $select .= ' WHERE l.id in (' . implode(' ,', $where) . ') ';
        $select .= ' AND lts.league_size_id = s.league_size_id AND s.id = l.schedule_id AND se.schedule_id = l.schedule_id AND se.game_day = lts.game_day';
        $select .= ' GROUP BY se.game_date';

        $plan = collect(DB::select($select));

        $returnhtml =  view("team/teamleague_pivot", ["plan" => $plan])->render();
        // Log::debug(print_r($returnhtml, true));
        return Response::json($returnhtml);
    }

    public function list_chart(Request $request)
    {
        Log::info('preparing team league chart.');

        $select = "select date_format(se.game_date, '%b-%d-%Y') AS 'gamedate', count(*) as 'homegames' ";
        $where =  array();
        $cols = array();

        foreach ($request->all() as $key => $league_no) {
            if (strpos($key, 'sel') !== false) {
                $league = explode(':', $key)[1];

                $where[] = '(l.id = ' . $league . ' AND lts.team_home = ' . $league_no . ')';
            }
        }

        $select .= ' FROM league_size_schemes lts, schedule_events se  , leagues l , schedules s ';
        $select .= ' WHERE (' . implode(' OR ', $where) . ') ';
        $select .= ' AND lts.league_size_id = s.league_size_id AND s.id = l.schedule_id AND se.schedule_id = l.schedule_id AND se.game_day = lts.game_day';
        $select .= " GROUP BY date_format(se.game_date, '%b-%d-%Y')";

        // Log::debug($select);
        $plan = collect(DB::select($select));
        // Log::debug(print_r($plan, true));

        return Response::json($plan);
    }

    public function Cartesian_Product_old($data)
    {
        ini_set('memory_limit', '2G');

        // get total
        $dim = 1;
        foreach ($data as $vec) {
            $dim = $dim * count($vec);
        };
        Log::debug('dimension is: ' . $dim);
        // limit to less than 100.000 combinations
        while ($dim > 200000) {
            $dim = 1;
            for ($i = 0; $i < count($data); $i++) {
                if (count($data[$i]) > 2) {
                    array_pop($data[$i]);
                };
                $dim = $dim * count($data[$i]);
            }
        }
        Log::debug('dimension is now: ' . $dim);

        $result = array(array());

        foreach ($data as $key => $values) {
            $append = array();

            foreach ($result as $product) {
                foreach ($values as $item) {
                    $product[$key] = $item;
                    $append[] = $product;
                }
            }

            $result = $append;
        }
        return $result;
    }

    public function build_comb($arr, $k)
    {
        if ($k == 0) {
            return array(array());
        }

        if (count($arr) == 0) {
            return array();
        }
        $head = $arr[0];

        $combos = array();
        $subcombos = $this->build_comb($arr, $k - 1);
        foreach ($subcombos as $subcombo) {
            array_unshift($subcombo, $head);
            $combos[] = $subcombo;
        }
        array_shift($arr);
        $combos = array_merge($combos, $this->build_comb($arr, $k));
        return $combos;
    }

    public function Cartesian_Product($data)
    {
        ini_set('memory_limit', '2G');

        // get total
        $dim = 1;
        foreach ($data as $vec) {
            $dim = $dim * count($vec);
        };
        // limit to less than 1000 combinations
        while ($dim > 250000) {
            $dim = 1;
            for ($i = 0; $i < count($data); $i++) {
                if (count($data[$i]) > 2) {
                    array_pop($data[$i]);
                };
                $dim = $dim * count($data[$i]);
            }
        }

        $result = array(array());

        foreach ($data as $key => $values) {
            $append = array();

            foreach ($result as $product) {
                foreach ($values as $item) {
                    // $product[$key] = $item;
                    if ($key == 0) {
                        $productt[0] = $item;
                    } else {
                        $productt[0] = array_merge($product[0], $item);
                    }
                    $append[] = $productt;
                }
            }

            $result = $append;
        }

        // pick 1000 random combinations
        if (count($result) > 5000) {
            $rand_keys = array_rand($result, 5000);
            $result = array_intersect_key($result, array_flip($rand_keys));
            $dim = 1000;
        }

        return $result;
    }

    /**
     * optimization for home games
     *
     * @return \Illuminate\Http\Response
     */
    public function propose_combination(Request $request)
    {
        Log::info('preparing to find possible team league no combinations');

        $leagues = array();
        $leagues['id'] = array();
        $leagues['team_no'] = array();
        $leagues['schedule_id'] = array();
        $leagues['size'] = array();
        $schedules = array();

        foreach ($request->all() as $key => $league_no) {
            if (strpos($key, 'sel') !== false) {
                $league = League::findOrFail(explode(':', $key)[1]);

                $leagues['id'][] = $league->id;
                $leagues['team_no'][] = $league_no;
                $leagues['schedule_id'][] = $league->schedule->id;
                $leagues['size'][] = range(1, $league->size);
                if (isset($schedules[$league->schedule->id]['count'])) {
                    $schedules[$league->schedule->id]['count'] += 1;
                } else {
                    $schedules[$league->schedule->id]['count'] = 1;
                }
                $schedules[$league->schedule->id]['size'] = $league->size;
            }
        }
        Log::info('reference data loaded.', ['league count' => count($request->input()) - 4] );

        $combos = array();
        foreach ($schedules as $key => $sd) {
            $combos[] = $this->build_comb(range(1, $sd['size']), $sd['count']);
        }

        // create an array with all combinations of team numbers

        // $data = $leagues['size'];
        // $combinations = $this->Cartesian_Product_old($data);
        $combinations = $this->Cartesian_Product($combos);
        Log::info('combinations defined.', ['combinations count' => count($combinations)]);

        $sel = "SELECT date(se.game_date) as gdate, l.id as glid, lts.team_home as ghome ";
        $sel .= " FROM schedule_events se, schedules s, league_size_schemes lts, leagues l ";
        $sel .= "WHERE se.schedule_id = s.id AND lts.league_size_id = s.league_size_id AND lts.game_day = se.game_day AND l.schedule_id = s.id  ";
        $sel .= "AND l.id in (" . implode(',', $leagues['id']) . ")";

        $filtercomb = DB::select($sel);
        $num_gdays = count($filtercomb);
        Log::info('game days loaded and filtered.', ['game days count' => $num_gdays] );

        // holds combinations for a given number of games/day
        $hdays_c = array();
        for ($i = 1; $i <= count($leagues['id']); $i++) {
            $hdays_c[$i] = array();
        }

        foreach ($combinations as $i => $c) {
            $homies = $filtercomb;
            for ($j = 0; $j < count($leagues['id']); $j++) {
                $homies = array_filter($homies, function ($v) use ($leagues, $c, $i, $j) {
                    if ($v->glid == $leagues['id'][$j]) {
                        if ($v->ghome == $c[0][$j]) {
                            return true;
                        } else {
                            return false;
                        };
                    } else {
                        return true;
                    };
                });
            }
            //Log::debug(print_r($homies,true));
            $gdays = array_count_values(array_column($homies, 'gdate'));
            //  Log::debug(print_r($gdays,true));
            $day_histogram = array_count_values($gdays);
            foreach ($day_histogram as $d => $c) {
                $hdays_c[$d][$i] = $c;
            }
            //Log::info('avg is :'.$avgday);
        }
        Log::info('statitics done.', ['home day counts' => count($hdays_c) ]);

        //get the combinations for min/max games/day
        $teams =  count($leagues['id']);

        // prepare combination to show in the UI
        $option_size = 20;

        for ($i = 1; $i <= $teams; $i++) {
            //$keys = array_slice(array_keys($hdays_c[$i],max($hdays_c[$i])),0,$option_size,true);
            arsort($hdays_c[$i]);
            $keys = array_slice($hdays_c[$i], 0, $option_size, true);
            $c = array_intersect_key($combinations, $keys);
            $resp['c_day'][$i] = array_values($c);
        }

        $resp['leagues'] = $leagues['id'];

        return Response::json($resp);
    }
}
