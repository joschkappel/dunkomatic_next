<?php

namespace App\Http\Controllers;

use App\League;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Exports\LeagueGameExport;
use Maatwebsite\Excel\Facades\Excel;


class ReportController extends Controller
{
  public function league_games($language, League $league)
  {
      Log::debug('am here inthe report');
      Excel::store(new LeagueGameExport($league->id), $league->shortname.'_games.xlsx');
      //$league = League::where('id',$league->id)->with('games')->get();
      //return view('reports/league_games', ['league' => $league]);
      return back();
  }
}
