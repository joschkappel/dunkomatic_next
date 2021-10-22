<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Region;

use Datatables;
use Bouncer;
use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;


class GameController extends Controller
{

      public function index($language, Region $region )
      {

        return view('game/game_list', ['region' => $region]);
      }

      public function datatable($language, Region $region )
      {
        // $leagues = $region->leagues()->pluck('id');
        // $games = Game::whereIn('league_id', $leagues)->orderBy('game_date')->get();
        $clubs = $region->clubs()->pluck('id');
        //$games = Game::whereIn('club_id_home', $clubs)->orWhereIn('club_id_guest',$clubs)->orderBy('game_date')->get();
        $games = Game::whereIn('club_id_home', $clubs)->orderBy('game_date')->get();

        $glist = datatables()::of($games);

        $glist =  $glist
          ->editColumn('game_time', function($game){
            return ($game->game_time == null) ? '' : Carbon::parse($game->game_time)->isoFormat('LT');
          })
          ->editColumn('game_no', function($game){
              return array('display' =>$game->game_no, 'sort'=>$game->game_no);
          })
          ->editColumn('game_date', function ($game) use ($language) {
                  return array('display' => Carbon::parse($game->game_date)->locale( $language )->isoFormat('ddd L'),
                               'ts'=>Carbon::parse($game->game_date)->timestamp,
                               'filter' => Carbon::parse($game->game_date)->locale( $language )->isoFormat('L'));
              })
          ->editColumn('gym_no', function ($game) {
                     return array('display' => $game->gym_no.' - '.$game['gym']['name'],
                                    'default' => $game->gym_no);
              })
            ->addColumn('game_league', function ($game) {
            return $game->league->shortname;
            })
          ->make(true);
          //Log::debug(print_r($glist,true));
          return $glist;

      }


}