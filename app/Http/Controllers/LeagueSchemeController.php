<?php

namespace App\Http\Controllers;

use App\LeagueTeamScheme;
use Illuminate\Http\Request;
use Datatables;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;

class LeagueSchemeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( )
    {
      return view('league/league_scheme_list');
    }

    public function list_piv( $size )
    {
        Log::debug('get league team scheme for '.$size.' teams');

        //$scheme = datatables::of(LeagueTeamScheme::query()->where('size', '=', $size));

        $scheme = collect(DB::select("select game_day,
          max(case when team_home = '1' then team_guest else ' ' end) as '1',
          max(case when team_home = '2' then team_guest else ' ' end) as '2',
          max(case when team_home = '3' then team_guest else ' ' end) as '3',
          max(case when team_home = '4' then team_guest else ' ' end) as '4',
          max(case when team_home = '5' then team_guest else ' ' end) as '5',
          max(case when team_home = '6' then team_guest else ' ' end) as '6',
          max(case when team_home = '7' then team_guest else ' ' end) as '7',
          max(case when team_home = '8' then team_guest else ' ' end) as '8',
          max(case when team_home = '9' then team_guest else ' ' end) as '9',
          max(case when team_home = '10' then team_guest else ' ' end) as '10',
          max(case when team_home = '11' then team_guest else ' ' end) as '11',
          max(case when team_home = '12' then team_guest else ' ' end) as '12',
          max(case when team_home = '13' then team_guest else ' ' end) as '13',
          max(case when team_home = '14' then team_guest else ' ' end) as '14',
          max(case when team_home = '15' then team_guest else ' ' end) as '15',
          max(case when team_home = '16' then team_guest else ' ' end) as '16'
        from league_team_schemes
        where size='".$size."'
        group by game_day"));

      //  Log::debug(print_r($test, true));

        $returnhtml =  view("league/league_scheme_pivot", ["scheme" => $scheme])->render();
        // Log::debug(print_r($returnhtml, true));
        return Response::json($returnhtml);
      }

}
