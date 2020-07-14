<?php

namespace App\Http\Controllers;

use App\Game;
use App\League;
use App\Team;
use App\ScheduleEvent;
use App\LeagueTeamScheme;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;

class LeagueGameController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \App\League  $league
     * @return \Illuminate\Http\Response
     */
    public function index(League $league)
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \App\League  $league
     * @return \Illuminate\Http\Response
     */
    public function create(League $league)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\League  $league
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, League $league)
    {
        // get size
        $league->load('schedule');
        // get scheme
        $scheme = collect(LeagueTeamScheme::where('size', $league->schedule['size'])->get());

        // get schedule
        $schedule = collect(ScheduleEvent::where('schedule_id', $league->schedule_id)->get());
        $gdate_by_day = $schedule->pluck('game_date','game_day');
        $wend_by_day = $schedule->pluck('full_weekend','game_day');

        // get teams
        $teams = collect(Team::where('league_id',$league->id)->with('club')->get());


        foreach ($scheme as $s){
          $gday = $gdate_by_day[ $s->game_day ];

          $hteam = $teams->firstWhere('league_no', $s->team_home);
          $gteam = $teams->firstWhere('league_no', $s->team_guest);
        //  Log::debug(print_r($hteam,true));

          Log::debug($s->game_day.' :: '.$gday.' ::'.$hteam['club']['shortname'].' - '.$gteam['club']['shortname']);
        }


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\League  $league
     * @param  \App\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function show(League $league, Game $game)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\League  $league
     * @param  \App\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function edit(League $league, Game $game)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\League  $league
     * @param  \App\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, League $league, Game $game)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\League  $league
     * @param  \App\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function destroy(League $league, Game $game)
    {
        //
    }
}
