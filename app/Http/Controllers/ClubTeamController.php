<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\Team;
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
        $club = $club->with('teams.league.teams')->with('teams.league.schedule')->first();
        Log::info('showing club team league char chart.', ['club-id' => $club->id]);
        return view('club.club_pickchar', ['club' => $club]);
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
