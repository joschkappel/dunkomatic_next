<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;

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
      $club = Club::where('id', $club->id)->with('teams.league.teams')->with('teams.league.schedule')->first();
      //dd($club);
      return view('club.club_pickchar', ['club'=>$club]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \App\Models\Club  $club
     * @return \Illuminate\Http\Response
     */
    public function create($language, Club $club)
    {
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
      Log::debug(print_r($request->all(),true));

      $data = $request->validate( Team::$createRules);

      $check = Team::create($data);

      return redirect()->action(
              'ClubController@dashboard', ['language'=>app()->getLocale(), 'club' => $club->id]
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
     * @param  \App\Models\Club  $club
     * @param  \App\Models\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function edit($language, Club $club, Team $team)
    {

      //Log::debug(print_r($team,true));
      $team->load('club','league');
      Log::debug(print_r($team,true));

      return view('team/team_edit', ['team' => $team]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Club  $club
     * @param  \App\Models\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Club $club, Team $team)
    {
        Log::debug(print_r($request->all(),true));

        $data = $request->validate(Team::$updateRules);

        Log::debug(print_r($team,true));
        $check = Team::where('id', $team->id)->update($data);

        return redirect()->action(
                'ClubController@dashboard', ['language'=>app()->getLocale(), 'club' => $team->club_id]
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Club  $club
     * @param  \App\Models\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function destroy( Club $club, Team $team)
    {
        // TBDnremove from league

        Team::find($team->id)->delete();
        return redirect()->back();
    }
}
