<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\Team;
use App\Enums\LeagueState;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ClubTeamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \App\Models\Club  $club
     * @return \Illuminate\Http\Response
     */
    public function index(Club $club)
    {
        //
    }

    public function pickchar($language, Club $club)
    {
        $team_total_cnt = $club->teams->whereNotNull('league_id')->count();
        $team_open_cnt = $club->teams->whereNotNull('league_id')->where('league.state', LeagueState::Selection())->count();
        Log::info('showing club team league char chart.', ['club-id' => $club->id]);
        return view('club.club_pickchar', ['club' => $club, 'team_total_cnt'=> $team_total_cnt, 'team_open_cnt'=>$team_open_cnt]);
    }

    /**
     * Get datatable with club teams and selected league chars
     *
     * @param  \App\Models\Club  $club
     * @return Databtable json
     */
    public function league_char_dt($language,  Club $club )
    {
        $clubs = $club->teams->whereNotNull('league_id')->where('league.state', LeagueState::Selection());

        $teamlist = datatables()::of($clubs);
        Log::info('preparing team list');
        return $teamlist
            ->rawColumns([
                'char_A', 'char_B', 'char_C', 'char_D', 'char_E', 'char_F',
                'char_G', 'char_H', 'char_I', 'char_K', 'char_L', 'char_M',
                'char_N', 'char_O', 'char_P', 'char_Q'
            ])
            ->addColumn('char_A', function ($t) {
                $col = ($t->preferred_league_no == 1) ? '<i class="fas fa-asterisk text-warning"></i>' : '';
                if ($t->league_no == 1) {
                    $col .= '<i class="far fa-dot-circle fa-lg text-success"></i>';
                } elseif ($t->league->load('teams')->teams->pluck('league_char')->contains('A')){
                    $col .= '<i class="far fa-frown text-danger"</i>';
                }
                return $col;
            })
            ->addColumn('char_B', function ($t) {
                $col = ($t->preferred_league_no == 2) ? '<i class="fas fa-asterisk text-warning"></i>' : '';
                if ($t->league_no == 2) {
                    $col .= '<i class="far fa-dot-circle fa-lg text-success"></i>';
                } elseif ($t->league->load('teams')->teams->pluck('league_char')->contains('B')){
                    $col .= '<i class="far fa-frown text-danger"</i>';
                }
                return $col;
            })
            ->addColumn('char_C', function ($t) {
                $col = ($t->preferred_league_no == 3) ? '<i class="fas fa-asterisk text-warning"></i>' : '';
                if ($t->league_no == 3) {
                    $col .= '<i class="far fa-dot-circle fa-lg text-success"></i>';
                } elseif ($t->league->load('teams')->teams->pluck('league_char')->contains('C')){
                    $col .= '<i class="far fa-frown text-danger"</i>';
                }
                return $col;
            })
            ->addColumn('char_D', function ($t) {
                $col = ($t->preferred_league_no == 4) ? '<i class="fas fa-asterisk text-warning"></i>' : '';
                if ($t->league_no == 4) {
                    $col .= '<i class="far fa-dot-circle fa-lg text-success"></i>';
                } elseif ($t->league->load('teams')->teams->pluck('league_char')->contains('D')){
                    $col .= '<i class="far fa-frown text-danger"</i>';
                }
                return $col;
            })
            ->addColumn('char_E', function ($t) {
                if ($t->league->size < 5) {
                    $col = '<i class="far fa-times-circle text-secondary"></i>';
                } else {
                    $col = ($t->preferred_league_no == 5) ? '<i class="fas fa-asterisk text-warning"></i>' : '';
                    if ($t->league_no == 5) {
                        $col .= '<i class="far fa-dot-circle fa-lg text-success"></i>';
                    } elseif ($t->league->load('teams')->teams->pluck('league_char')->contains('E')){
                        $col .= '<i class="far fa-frown text-danger"</i>';
                    }
                }
                return $col;
            })
            ->addColumn('char_F', function ($t) {
                if ($t->league->size < 6) {
                    $col = '<i class="far fa-times-circle text-secondary"></i>';
                } else {
                    $col = ($t->preferred_league_no == 6) ? '<i class="fas fa-asterisk text-warning"></i>' : '';
                    if ($t->league_no == 6) {
                        $col .= '<i class="far fa-dot-circle fa-lg text-success"></i>';
                    } elseif ($t->league->load('teams')->teams->pluck('league_char')->contains('F')){
                        $col .= '<i class="far fa-frown text-danger"</i>';
                    }
                }
                return $col;
            })
            ->addColumn('char_G', function ($t) {
                if ($t->league->size < 7) {
                    $col = '<i class="far fa-times-circle text-secondary"></i>';
                } else {
                    $col = ($t->preferred_league_no == 7) ? '<i class="fas fa-asterisk text-warning"></i>' : '';
                    if ($t->league_no == 7) {
                        $col .= '<i class="far fa-dot-circle fa-lg text-success"></i>';
                    } elseif ($t->league->load('teams')->teams->pluck('league_char')->contains('G')){
                        $col .= '<i class="far fa-frown text-danger"</i>';
                    }
                }
                return $col;
            })
            ->addColumn('char_H', function ($t) {
                if ($t->league->size < 8) {
                    $col = '<i class="far fa-times-circle text-secondary"></i>';
                } else {
                    $col = ($t->preferred_league_no == 8) ? '<i class="fas fa-asterisk text-warning"></i>' : '';
                    if ($t->league_no == 8) {
                        $col .= '<i class="far fa-dot-circle fa-lg text-success"></i>';
                    } elseif ($t->league->load('teams')->teams->pluck('league_char')->contains('H')){
                        $col .= '<i class="far fa-frown text-danger"</i>';
                    }
                }
                return $col;
            })
            ->addColumn('char_I', function ($t) {
                if ($t->league->size < 9) {
                    $col = '<i class="far fa-times-circle text-secondary"></i>';
                } else {
                    $col = ($t->preferred_league_no == 9) ? '<i class="fas fa-asterisk text-warning"></i>' : '';
                    if ($t->league_no == 9) {
                        $col .= '<i class="far fa-dot-circle fa-lg text-success"></i>';
                    } elseif ($t->league->load('teams')->teams->pluck('league_char')->contains('I')){
                        $col .= '<i class="far fa-frown text-danger"</i>';
                    }
                }
                return $col;
            })
            ->addColumn('char_K', function ($t) {
                if ($t->league->size < 10) {
                    $col = '<i class="far fa-times-circle text-secondary"></i>';
                } else {
                    $col = ($t->preferred_league_no == 10) ? '<i class="fas fa-asterisk text-warning"></i>' : '';
                    if ($t->league_no == 10) {
                        $col .= '<i class="far fa-dot-circle fa-lg text-success"></i>';
                    } elseif ($t->league->load('teams')->teams->pluck('league_char')->contains('K')){
                        $col .= '<i class="far fa-frown text-danger"</i>';
                    }
                }
                return $col;
            })
            ->addColumn('char_L', function ($t) {
                if ($t->league->size < 11) {
                    $col = '<i class="far fa-times-circle text-secondary"></i>';
                } else {
                    $col = ($t->preferred_league_no == 11) ? '<i class="fas fa-asterisk text-warning"></i>' : '';
                    if ($t->league_no == 11) {
                        $col .= '<i class="far fa-dot-circle fa-lg text-success"></i>';
                    } elseif ($t->league->load('teams')->teams->pluck('league_char')->contains('L')){
                        $col .= '<i class="far fa-frown text-danger"</i>';
                    }
                }
                return $col;
            })
            ->addColumn('char_M', function ($t) {
                if ($t->league->size < 12) {
                    $col = '<i class="far fa-times-circle text-secondary"></i>';
                } else {
                    $col = ($t->preferred_league_no == 12) ? '<i class="fas fa-asterisk text-warning"></i>' : '';
                    if ($t->league_no == 12) {
                        $col .= '<i class="far fa-dot-circle fa-lg text-success"></i>';
                    } elseif ($t->league->load('teams')->teams->pluck('league_char')->contains('M')){
                        $col .= '<i class="far fa-frown text-danger"</i>';
                    }
                }
                return $col;
            })
            ->addColumn('char_N', function ($t) {
                if ($t->league->size < 13) {
                    $col = '<i class="far fa-times-circle text-secondary"></i>';
                } else {
                    $col = ($t->preferred_league_no == 13) ? '<i class="fas fa-asterisk text-warning"></i>' : '';
                    if ($t->league_no == 13) {
                        $col .= '<i class="far fa-dot-circle fa-lg text-success"></i>';
                    } elseif ($t->league->load('teams')->teams->pluck('league_char')->contains('N')){
                        $col .= '<i class="far fa-frown text-danger"</i>';
                    }
                }
                return $col;
            })
            ->addColumn('char_O', function ($t) {
                if ($t->league->size < 14) {
                    $col = '<i class="far fa-times-circle text-secondary"></i>';
                } else {
                    $col = ($t->preferred_league_no == 14) ? '<i class="fas fa-asterisk text-warning"></i>' : '';
                    if ($t->league_no == 14) {
                        $col .= '<i class="far fa-dot-circle fa-lg text-success"></i>';
                    } elseif ($t->league->load('teams')->teams->pluck('league_char')->contains('O')){
                        $col .= '<i class="far fa-frown text-danger"</i>';
                    }
                }
                return $col;
            })
            ->addColumn('char_P', function ($t) {
                if ($t->league->size < 15) {
                    $col = '<i class="far fa-times-circle text-secondary"></i>';
                } else {
                    $col = ($t->preferred_league_no == 15) ? '<i class="fas fa-asterisk text-warning"></i>' : '';
                    if ($t->league_no == 15) {
                        $col .= '<i class="far fa-dot-circle fa-lg text-success"></i>';
                    } elseif ($t->league->load('teams')->teams->pluck('league_char')->contains('P')){
                        $col .= '<i class="far fa-frown text-danger"</i>';
                    }
                }
                return $col;
            })
            ->addColumn('char_Q', function ($t) {
                if ($t->league->size < 16) {
                    $col = '<i class="far fa-times-circle text-secondary"></i>';
                } else {
                    $col = ($t->preferred_league_no == 16) ? '<i class="fas fa-asterisk text-warning"></i>' : '';
                    if ($t->league_no == 16) {
                        $col .= '<i class="far fa-dot-circle fa-lg text-success"></i>';
                    } elseif ($t->league->load('teams')->teams->pluck('league_char')->contains('Q')){
                        $col .= '<i class="far fa-frown text-danger"</i>';
                    }
                }
                return $col;
            })
            ->make(true);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \App\Models\Club  $club
     * @return \Illuminate\Http\Response
     */
    public function create($language, Club $club)
    {
        Log::info('create new team for club', ['club-id' => $club->id]);
        return view('team/team_new', ['club' => $club]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Club  $club
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Club $club)
    {
        $data = $request->validate(Team::getCreateRules());
        Log::info('team form data validated OK.');

        $team = new Team($data);
        $club->teams()->save($team);
        Log::notice('new team created for club', ['club-id'=>$club->id, 'team-id'=>$team->id]);

        return redirect()->action(
            'ClubController@dashboard',
            ['language' => app()->getLocale(), 'club' => $club->id]
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Club  $club
     * @param  \App\Models\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function show(Club $club, Team $team)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function edit($language, Team $team)
    {
        Log::info('editing team.', ['team-id'=>$team->id]);
        $team->load('club', 'league');

        return view('team/team_edit', ['team' => $team]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Team $team)
    {
        if ($request['training_time'] == 'Invalid date') {
            $request['training_time'] = null;
        }
        if ($request['preferred_game_time'] == 'Invalid date') {
            $request['preferred_game_time'] = null;
        }
        $data = $request->validate(Team::getUpdateRules());
        Log::info('team form data validated OK.');

        $check = $team->update($data);
        $team->refresh();
        Log::notice('team updated', ['team-id'=> $team->id]);

        return redirect()->action(
            'ClubController@dashboard',
            ['language' => app()->getLocale(), 'club' => $team->club_id]
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function destroy(Team $team)
    {
        // TBD remove from league ?? or remove games ?

        $check = $team->delete();
        Log::notice('team deleted.', ['team-id'=>$team->id]);

        return redirect()->back();
    }
}
