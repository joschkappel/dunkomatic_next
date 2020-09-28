<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\League;
use App\Models\Team;
use App\Models\Gym;
use App\Models\ScheduleEvent;
use App\Models\LeagueTeamScheme;

use Datatables;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;
use Carbon\CarbonImmutable;

class LeagueGameController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \App\Models\League  $league
     * @return \Illuminate\Http\Response
     */
    public function index($language, League $league)
    {
       return view('game/league_game_list', ['league' => $league]);
    }
    public function datatable($language, League $league)
    {
      $games = $league->games()->get();
      $glist = datatables::of($games);

      $glist =  $glist
        ->rawColumns(['game_no.display'])
        ->editColumn('game_time', function($game){
          return Carbon::parse($game->game_time)->isoFormat('LT');
        })
        ->editColumn('game_no', function($game){
            $link = '<a href="#" id="gameEditLink" data-id="'.$game->id.
                    '" data-game-date="'.$game->game_date.'" data-game-time="'.$game->game_time.'" data-club-id-home"'.$game->club_id_home.
                    '" data-gym-no="'.$game->gym_no.'" data-gym-id="'.$game->gym_id.'" data-league="'.$game->league['shortname'].
                    '">'.$game->game_no.' <i class="fas fa-arrow-circle-right"></i></a>';
            return array('display' =>$link, 'sort'=>$game->game_no);
        })
        ->editColumn('game_date', function ($game) use ($language) {
                return array('display' => Carbon::parse($game->game_date)->locale( $language )->isoFormat('ddd L'),
                             'ts'=>Carbon::parse($game->game_date)->timestamp,
                             'filter' => Carbon::parse($game->game_date)->locale( $language )->isoFormat('L'));
            })
        ->editColumn('gym_no', function ($game) {
                return array('display' => $game->gym_no,
                             'default' => $game->gym_no);
            })
        ->make(true);
        Log::debug(print_r($glist,true));
        return $glist;
      }
    /**
     * Show the form for creating a new resource.
     *
     * @param  \App\Models\League  $league
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
     * @param  \App\Models\League  $league
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

        // get teams
        $teams = collect(Team::where('league_id',$league->id)->with('club')->get());


        foreach ($scheme as $s){
          $gday = $gdate_by_day[ $s->game_day ];

          $hteam = $teams->firstWhere('league_no', $s->team_home);
          $gteam = $teams->firstWhere('league_no', $s->team_guest);

          $g = array();
          $g['region'] = $league->region;
          $g['game_plandate'] = $gday;
          if (isset($hteam['preferred_game_day'])){
            $pref_gday = $hteam['preferred_game_day'] % 7;
            $g['game_date'] = $gday->next($pref_gday);
          } else {
            $g['game_date'] = $gday;
          };
          $g['gym_no'] = "1";
          $g['referee_1'] = "";
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

          //Log::debug(print_r($g, true));
          Game::updateOrCreate(['league_id' => $league->id, 'game_no' => $s->game_no], $g);
        }
        League::find($league->id)->update(['generated_at' => now()]);

        return Response::json(['success' => 'all good'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\League  $league
     * @param  \App\Models\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function show(League $league, Game $game)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\League  $league
     * @param  \App\Models\Game  $game
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
     * @param  \App\Models\League  $league
     * @param  \App\Models\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Game $game)
    {
        //Log::debug(print_r($game, true));
        Log::debug(print_r($request->all(),true));
        $game->game_time = Carbon::parse($request->game_time)->format('H:i');
        $game->game_date = $request->game_date;
        if ($request->gym_id ){
          $gym = Gym::where('id',$request->gym_id )->get();
          //Log::debug(print_r($gym,true));
          $game->gym_no = $gym[0]->gym_no;
          $game->gym_id = $gym[0]->id;
        }
        //Log::debug(print_r($game,true));
        $game->save();
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\League  $league
     * @param  \App\Models\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function destroy_game(League $league)
    {
        $league->games()->delete();
        League::find($league->id)->update(['generated_at' => null]);
        return Response::json(['success' => 'all good'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\League  $league
     * @param  \App\Models\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function destroy_noshow_game(League $league)
    {
        $check = $league->games()->where(function (Builder $query) {
            return $query->whereNull('club_id_home')
                         ->orWhereNull('club_id_guest');
        })->delete();
        //Log::debug($check);
        return Response::json(['success' => 'all good'], 200);
    }
}
