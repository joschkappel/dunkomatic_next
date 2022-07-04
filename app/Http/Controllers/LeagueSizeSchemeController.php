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
            ->rawColumns(['1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16'])
            ->editColumn('1', function ($d) { return $d->{1} == ' ' ? '' : '<span class="badge text-sm badge-pill badge-secondary">1 - '.$d->{1}.'</span>';  })
            ->editColumn('2', function ($d) { return $d->{2} == ' ' ? '' : '<span class="badge text-sm badge-pill badge-primary">2 - '.$d->{2}.'</span>';  })
            ->editColumn('3', function ($d) { return $d->{3} == ' ' ? '' : '<span class="badge text-sm badge-pill badge-secondary">3 - '.$d->{3}.'</span>';  })
            ->editColumn('4', function ($d) { return $d->{4} == ' ' ? '' : '<span class="badge text-sm badge-pill badge-primary">4 - '.$d->{4}.'</span>';  })
            ->editColumn('5', function ($d) { return $d->{5} == ' ' ? '' : '<span class="badge text-sm badge-pill badge-secondary">5 - '.$d->{5}.'</span>';  })
            ->editColumn('6', function ($d) { return $d->{6} == ' ' ? '' : '<span class="badge text-sm badge-pill badge-primary">6 - '.$d->{6}.'</span>';  })
            ->editColumn('7', function ($d) { return $d->{7} == ' ' ? '' : '<span class="badge text-sm badge-pill badge-secondary">7 - '.$d->{7}.'</span>';  })
            ->editColumn('8', function ($d) { return $d->{8} == ' ' ? '' : '<span class="badge text-sm badge-pill badge-primary">8 - '.$d->{8}.'</span>';  })
            ->editColumn('9', function ($d) { return $d->{9} == ' ' ? '' : '<span class="badge text-sm badge-pill badge-secondary">9 - '.$d->{9}.'</span>';  })
            ->editColumn('10', function ($d) { return $d->{10} == ' ' ? '' : '<span class="badge text-sm badge-pill badge-secondary">10 - '.$d->{10}.'</span>';  })
            ->editColumn('11', function ($d) { return $d->{11} == ' ' ? '' : '<span class="badge text-sm badge-pill badge-primary">11 - '.$d->{11}.'</span>';  })
            ->editColumn('12', function ($d) { return $d->{12} == ' ' ? '' : '<span class="badge text-sm badge-pill badge-secondary">12 - '.$d->{12}.'</span>';  })
            ->editColumn('13', function ($d) { return $d->{13} == ' ' ? '' : '<span class="badge text-sm badge-pill badge-primary">13 - '.$d->{13}.'</span>';  })
            ->editColumn('14', function ($d) { return $d->{14} == ' ' ? '' : '<span class="badge text-sm badge-pill badge-secondary">14 - '.$d->{14}.'</span>';  })
            ->editColumn('15', function ($d) { return $d->{15} == ' ' ? '' : '<span class="badge text-sm badge-pill badge-primary">15 - '.$d->{15}.'</span>';  })
            ->editColumn('16', function ($d) { return $d->{16} == ' ' ? '' : '<span class="badge text-sm badge-pill badge-secondary">16 - '.$d->{16}.'</span>';  })
            ->make(true);
    }

    /**
     * Display a matrix showing the home game matches between team league numbers
     *
     * @param \App\Models\LeagueSize $size
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function list_match(LeagueSize $size){
        Log::info('preparing laegue scheme match table.', ['league-size-id'=>$size->id]);

        $scheme = $size->schemes;
        $league_no_a = $scheme->groupBy('team_home')->sortKeys();
        $league_no_b = $scheme->groupBy('team_home')->sortKeys();

        $matches = collect();
        $max_days = ($size->size - 1) ;

        foreach($league_no_a as $no_a){
            $row = array('league_no'=> $no_a->first()->team_home);
            foreach($league_no_b as $no_b){
                // only do have matrix
                if ( $no_b->first()->team_home >= $no_a->first()->team_home ){
                    $common_homegames = $no_a->pluck('game_day')->intersect( $no_b->pluck('game_day'))->count();
                    if (  $no_b->first()->team_home == $no_a->first()->team_home ){
                        $cell = '<span class="badge text-sm badge-pill badge-danger text-white">';
                    } else {
                        if (  $common_homegames >= $max_days*0.8 ){
                            $cell = '<span class="badge text-sm badge-pill text-white" style="background-color:SeaGreen">';
                        } elseif (  $common_homegames >= $max_days*0.6 ){
                            $cell = '<span class="badge text-sm badge-pill text-white" style="background-color:LimeGreen">';
                        } elseif (  $common_homegames >= $max_days*0.6 ){
                            $cell = '<span class="badge text-sm badge-pill " style="background-color:GreenYellow">';
                        } else {
                            $cell = '<span>';
                        }
                    }

                    $cell .= ($common_homegames*2).'</span>';
                    $row += [$no_b->first()->team_home => $cell  ];
                } else {
                    $row += [$no_b->first()->team_home => '' ];
                }
            }
            // populate missing table cols
            foreach(range($size->size+1, 16) as $i){
                $row += [$i=>''];
            }

            $matches->push($row);
        }

        $matchlist = datatables($matches);
        Log::info('Scheme matches calculated');

        return $matchlist
            ->rawColumns(['1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16'])
            ->make(true);

    }

    /**
     * Display a matrix showing the home game matches between 2 schemes
     * @param \App\Models\LeagueSize $size
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function list_compare(LeagueSize $size1, LeagueSize $size2){
        Log::info('preparing laegue scheme comparison table.', ['league-size-id1'=>$size1->id, 'league-size-id2'=>$size2->id]);

        $scheme1 = $size1->schemes;
        $league_no_a = $scheme1->groupBy('team_home')->sortKeys();
        $scheme2 = $size2->schemes;
        $league_no_b = $scheme2->groupBy('team_home')->sortKeys();

        $matches = collect();
        $max_days = min(($size1->size - 1),($size2->size - 1)) ;

        foreach($league_no_a as $no_a){
            $row = array('league_no'=> $no_a->first()->team_home);
            foreach($league_no_b as $no_b){
                $common_homegames = $no_a->pluck('game_day')->intersect( $no_b->pluck('game_day'))->count();
                if (  $common_homegames == $max_days ){
                    $cell = '<span class="badge text-sm badge-pill text-white badge-danger">';
                } elseif (  $common_homegames >= $max_days*0.8 ){
                    $cell = '<span class="badge text-sm badge-pill text-white" style="background-color:SeaGreen">';
                } elseif (  $common_homegames >= $max_days*0.6 ){
                    $cell = '<span class="badge text-sm badge-pill text-white" style="background-color:LimeGreen">';
                } elseif (  $common_homegames >= $max_days*0.6 ){
                    $cell = '<span class="badge text-sm badge-pill " style="background-color:GreenYellow">';
                } else {
                    $cell = '<span>';
                }

                $cell .= ($common_homegames*2).'</span>';
                $row += [$no_b->first()->team_home => $cell  ];
            }
            // populate missing table cols
            foreach(range($size2->size+1, 16) as $i){
                $row += [$i=>''];
            }

            $matches->push($row);
        }

        $matchlist = datatables($matches);
        Log::info('Scheme comparison calculated');

        return $matchlist
            ->rawColumns(['1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16'])
            ->make(true);

    }
}
