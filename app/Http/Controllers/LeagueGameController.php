<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\League;
use App\Models\Club;
use App\Models\Gym;
use App\Traits\LeagueFSM;
use App\Traits\GameManager;

use App\Notifications\LeagueGamesGenerated;

use Datatables;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;
use Carbon\CarbonImmutable;

class LeagueGameController extends Controller
{
    use LeagueFSM, GameManager;

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
      $glist = datatables()::of($games);

      $glist =  $glist
        ->rawColumns(['game_no.display'])
        ->editColumn('game_time', function($game){
          return ($game->game_time == null) ? '' : Carbon::parse($game->game_time)->isoFormat('LT');
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
        //Log::debug(print_r($glist,true));
        return $glist;
      }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Models\League  $league
     * @return \Illuminate\Http\Response
     */
    public function store(League $league)
    {

        $this->create_games($league);
        $this->close_freeze($league);

        return Response::json(['success' => 'all good'], 200);
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
        $data = $request->validate( [
            'gym_id' => 'required|exists:gyms,id',
            'game_date' => 'required|date|after:today',
            'game_time' => 'required|date_format:H:i'
        ]);

        $data['game_time'] = Carbon::parse($data['game_time'])->format('H:i');

        // Get GYM NO
        $data['gym_no'] = Gym::find($data['gym_id'])->gym_no;
        //Log::debug(print_r($game,true));
        $game->update($data);
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
        $this->open_freeze($league);
        $league->refresh();
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
