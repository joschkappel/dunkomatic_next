<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\League;
use App\Models\Gym;
use App\Models\Team;
use App\Traits\LeagueFSM;
use App\Traits\GameManager;


use Datatables;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;


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
    /**
     * Get a game by game number
     *
     * @param  \App\Models\League  $league
     * @param  $game_no
     * @return \Illuminate\Http\Response
     */
    public function show_by_number(League $league, $game_no)
    {
       $game = $league->games->where('game_no', $game_no)->first();

       return Response::json( $game, 200);
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
                    '" data-game-date="'.$game->game_date.'" data-game-time="'.$game->game_time.'" data-club-id-home="'.$game->club_id_home.
                    '" data-gym-no="'.$game->gym_no.'" data-gym-id="'.$game->gym_id.'" data-league="'.$game->league['shortname'].
                    '" data-team-home="'.$game->team_home.'" data-team-id-home="'.$game->team_id_home.'" data-team-guest="'.$game->team_guest.'" data-team-id-guest="'.$game->team_id_guest.
                    '" data-game-no="'.$game->game_no.'" data-league-id="'.$game->league_id.
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
        Log::debug(print_r($request->all(),true));

        if ($game->league->schedule->custom_events){
            $maxgames = $game->league->size * ( $game->league->size - 1);
        
            $data = Validator::make($request->all(), [
                'gym_id' => 'required|exists:gyms,id',
                'game_date' => 'required|date|after:today',
                'game_time' => 'required|date_format:H:i',
                'team_id_home' => 'exists:teams,id|different:team_id_guest',
                'team_id_guest' => 'exists:teams,id|different:team_id_home',
           //     'game_no' => 'integer|between:1,12',
                'game_no' => [Rule::unique('games')->where(function ($query) use ($game) {
                    return $query->where('league_id', $game->league->id)->where('game_no','!=',$game->game_no);
                    }), 'integer', 'between:1,'.$maxgames] 
                ])->validate();
                    // handle new home team
                if ( $data['team_id_home'] != $game->team_id_home){
                    $team_home = Team::find($data['team_id_home']);
                    $data['club_id_home'] = $team_home->club->id;
                    $data['team_home'] = $team_home->club->shortname.$team_home->team_no;
                } else {
                    unset($data['team_id_home']);
                }

                // handle new guest team
                if ( $data['team_id_guest'] != $game->team_id_guest){
                    $team_guest = Team::find($data['team_id_guest']);
                    $data['club_id_guest'] = $team_guest->club->id;
                    $data['team_guest'] = $team_guest->club->shortname.$team_guest->team_no;
                } else {
                    unset($data['team_id_guest']);
                }        
        } else {
            $data = Validator::make($request->all(), [
                'gym_id' => 'required|exists:gyms,id',
                'game_date' => 'required|date|after:today',
                'game_time' => 'required|date_format:H:i'
            ])->validate();
        }
        
        $data['game_time'] = Carbon::parse($data['game_time'])->format('H:i');

        // Get GYM NO
        $data['gym_no'] = Gym::find($data['gym_id'])->gym_no;



        Log::debug(print_r($data, true));
        $game->update($data);
        return response()->json(['success'=>'Data is successfully added']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\League  $league
     * @param  \App\Models\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function update_home(Request $request, Game $game)
    {
        //Log::debug(print_r($game, true));
        Log::debug(print_r($request->all(),true));
        $data = Validator::make($request->all(), [
            'gym_id' => 'required|exists:gyms,id',
            'game_date' => 'required|date|after:today',
            'game_time' => 'required|date_format:H:i'
        ])->validate();

        $data['game_time'] = Carbon::parse($data['game_time'])->format('H:i');

        // Get GYM NO
        $data['gym_no'] = Gym::find($data['gym_id'])->gym_no;
        //Log::debug(print_r($game,true));
        $game->update($data);
        return response()->json(['success'=>'Data is successfully added']);
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
