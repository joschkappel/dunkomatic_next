<?php

namespace App\Http\Controllers;

use App\Models\LeagueSizeScheme;
use App\Models\LeagueSize;
use Illuminate\Http\Request;
use Datatables;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;

class LeagueSizeSchemeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     *
     */
    public function index()
    {
        Log::info('showing laegue size scheme list.');
        return view('league/league_scheme_list');
    }

    /**
     * Display a pivot list (leagues as columns)
     *
     * @param \App\Models\LeagueSize $size
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function list_piv(LeagueSize $size)
    {
        Log::info('preparing laegue scheme pivot table.', ['league-size-id'=>$size->id]);

        //$scheme = datatables()::of(LeagueTeamScheme::query()->where('size', '=', $size));

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
        from league_size_schemes
        where league_size_id='" . $size->id . "'
        group by game_day"));

        //  Log::debug(print_r($test, true));

        $schemelist = datatables()::of($scheme);

        return $schemelist
            ->editColumn('1', function ($d) { return $d->{1} == ' ' ? '' : '1 - '.$d->{1};  })
            ->editColumn('2', function ($d) { return $d->{2} == ' ' ? '' : '2 - '.$d->{2};  })
            ->editColumn('3', function ($d) { return $d->{3} == ' ' ? '' : '3 - '.$d->{3};  })
            ->editColumn('4', function ($d) { return $d->{4} == ' ' ? '' : '4 - '.$d->{4};  })
            ->editColumn('5', function ($d) { return $d->{5} == ' ' ? '' : '5 - '.$d->{5};  })
            ->editColumn('6', function ($d) { return $d->{6} == ' ' ? '' : '6 - '.$d->{6};  })
            ->editColumn('7', function ($d) { return $d->{7} == ' ' ? '' : '7 - '.$d->{7};  })
            ->editColumn('8', function ($d) { return $d->{8} == ' ' ? '' : '8 - '.$d->{8};  })
            ->editColumn('9', function ($d) { return $d->{9} == ' ' ? '' : '9 - '.$d->{9};  })
            ->editColumn('10', function ($d) { return $d->{10} == ' ' ? '' : '10 - '.$d->{10};  })
            ->editColumn('11', function ($d) { return $d->{11} == ' ' ? '' : '11 - '.$d->{11};  })
            ->editColumn('12', function ($d) { return $d->{12} == ' ' ? '' : '12 - '.$d->{12};  })
            ->editColumn('13', function ($d) { return $d->{13} == ' ' ? '' : '13 - '.$d->{13};  })
            ->editColumn('14', function ($d) { return $d->{14} == ' ' ? '' : '14 - '.$d->{14};  })
            ->editColumn('15', function ($d) { return $d->{15} == ' ' ? '' : '15 - '.$d->{15};  })
            ->editColumn('16', function ($d) { return $d->{16} == ' ' ? '' : '16 - '.$d->{16};  })
            ->make(true);
    }
}
