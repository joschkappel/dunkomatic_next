<?php

namespace App\Http\Controllers;

use App\Club;
use App\Game;
use Illuminate\Http\Request;

use Datatables;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;
use Carbon\CarbonImmutable;

use App\Imports\HomeGamesImport;
use Illuminate\Support\Facades\Storage;


class ClubGameController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \App\Club  $club
     * @return \Illuminate\Http\Response
     */
    public function index(Club $club)
    {
        //
    }
    public function chart($language, Club $club)
    {
        return view('club/club_hgame_chart', ['club' => $club]);
    }
    public function list_home($language, Club $club)
    {

        // get games that overlapp (within 90 minutes)
        // $duplicates = DB::table('games')
        //                ->select(DB::raw('game_date' ,'gym_no', 'game_time'))
        //                ->where('club_id_home', $club->id)
        //                ->groupBy('game_date', 'gym_no', 'game_time')
        //                ->havingRaw('COUNT(*) > ?', [1])
        //                ->pluck('game_date');

        $select90 = 'SELECT distinct ga.id
               FROM games ga
               JOIN games gb on ga.game_time <= date_add(gb.game_time, INTERVAL 89 minute)
                   and date_add(ga.game_time,interval 89 minute) >= gb.game_time
                   and ga.club_id_home=gb.club_id_home and ga.gym_no = gb.gym_no and ga.game_date = gb.game_date
                   and ga.id != gb.id
               WHERE ga.club_id_home='.$club->id.' ORDER BY ga.game_date DESC, ga.club_id_home ASC';

         $ogames90 = collect(DB::select($select90))->pluck('id');

         $select60 = 'SELECT distinct ga.id
                FROM games ga
                JOIN games gb on ga.game_time <= date_add(gb.game_time, INTERVAL 59 minute)
                    and date_add(ga.game_time,interval 59 minute) >= gb.game_time
                    and ga.club_id_home=gb.club_id_home and ga.gym_no = gb.gym_no and ga.game_date = gb.game_date
                    and ga.id != gb.id
                WHERE ga.club_id_home='.$club->id.' ORDER BY ga.game_date DESC, ga.club_id_home ASC';

         $ogames60 = collect(DB::select($select60))->pluck('id');

        //Log::debug(print_r($ogames,true));

        Log::debug('get home games for club '.$club->id);
        $games = Game::query()->where('club_id_home', $club->id)->with('league','gym')->get();
        $glist = datatables::of($games);

        $glist =  $glist
          ->rawColumns(['game_no.display','duplicate'])
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
          ->addColumn('duplicate', function ($game) use ($ogames90, $ogames60){
            $warning='';
            if ($ogames60->contains($game->id)){
              Log::info('found it in 60');
              $warning = '<div class="text-center"><spawn class="bg-danger px-2"> <i class="fa fa-exclamation-triangle"></i>60</spawn></div>';
            };
            if ($ogames90->contains($game->id)){
              Log::info('found it in 90');
              $warning = $warning.'<div class="text-center"><spawn class="bg-warning px-2"> <i class="fa fa-exclamation-triangle"></i>90</spawn></div>';
            };
            return $warning;
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
          ->make(true);
          //Log::debug(print_r($glist,true));
          return $glist;
    }

    public function chart_home(Club $club)
    {
      $select = "select date_format(g.game_date, '%b-%d-%Y') AS 't', ";
      $select .= " time_format(g.game_time, '%H') AS 'ghour', time_format(g.game_time, '%i') AS 'gmin', g.gym_no AS 'gym', gy.name AS 'gymname' ";
      $select .= ' FROM games g, gyms gy ';
      $select .= ' WHERE g.gym_id=gy.id AND g.club_id_home='.$club->id;
      $select .= " ORDER BY g.gym_no, date_format(g.game_date, '%b-%d-%Y') ASC, g.game_time ASC";

      // Log::debug($select);
      $hgames = collect(DB::select($select));
      $hg_by_gym = array();
      $cgym = '';

      foreach ($hgames as $hg){
        if ( $cgym != $hg->gym){
          $cgym = $hg->gym;
          $hg_by_gym[$cgym]['label'] = $hg->gymname;
          $hg_by_gym[$cgym]['data'] = new \Illuminate\Support\Collection;
        }
        $hg->y = intval($hg->ghour)+($hg->gmin = intval($hg->gmin)/60);
        unset($hg->gmin);
        unset($hg->ghour);
        unset($hg->gym);
        $hg_by_gym[$cgym]['data']->push($hg);
      }

     //Log::debug(print_r($hg_by_gym, true));

     return Response::json($hg_by_gym);
    }

    /**
     * Show the form for uploading game files
     *
     * @param  \App\Club  $club
     * @return \Illuminate\Http\Response
     */
    public function upload($language, Club $club)
    {
        return view('club/club_homegame_upload', ['club' => $club]);
    }

    /**
     * update games with file contents
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Club  $club
     * @return \Illuminate\Http\Response
     */
    public function import(Request $request, $language, Club $club)
    {
        // Log::debug(print_r($request->all(),true));
        //$fname = $request->gfile->getClientOriginalName();
        //$fname = $club->shortname.'_homegames.'.$request->gfile->extension();
        $errors = [];

        $path = $request->gfile->store('homegames');
        $hgImport = new HomeGamesImport($club->id);
        try {
          $hgImport->import($path, 'local', \Maatwebsite\Excel\Excel::XLSX);
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
          $failures = $e->failures();
          $ebag = array();
          foreach ($failures as $failure) {
              $ebag[] = 'Zeile '.$failure->row().', Spalte '.$failure->attribute().', Wert  ": '.$failure->errors()[0];
          }
          Log::debug(print_r($ebag,true));
          Storage::delete($path);
          return redirect()->back()->withErrors($ebag);
        }
        Storage::delete($path);

        //return redirect()->back();
        return redirect()->route('club.edit.homegame', ['language'=>$language, 'club' => $club]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \App\Club  $club
     * @return \Illuminate\Http\Response
     */
    public function create(Club $club)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Club  $club
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Club $club)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Club  $club
     * @param  \App\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function show(Club $club, Game $game)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Club  $club
     * @param  \App\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function edit(Club $club, Game $game)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Club  $club
     * @param  \App\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Club $club, Game $game)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Club  $club
     * @param  \App\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function destroy(Club $club, Game $game)
    {
        //
    }
}
