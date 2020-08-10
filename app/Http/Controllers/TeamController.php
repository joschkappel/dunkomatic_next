<?php

namespace App\Http\Controllers;

use App\Team;
use App\Club;
use App\League;
use App\Schedule;

use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store_plan(Request $request)
    {
      Log::info(print_r($request->input(), true));
      $upperArr = range('A', 'Q');
      $club_id = $request->input('club_id');

      foreach ($request->input() as $key => $league_no) {
        if ( strpos($key, 'sel') !== false ){
          $team_id = explode(':', $key)[2];

          // update team league character
          $team = Team::find($team_id);
          $team->league_no = $league_no;
          $team->league_char = $upperArr[ $league_no - 1 ];

          $check = $team->save();
        }
      }

      return Response::json($check);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function show(Team $team)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function edit(Team $team)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Team $team)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function destroy(Team $team)
    {
        //
    }

    /**
     * Attach team to league
     *
     * @param  \App\League  $league
     * @return \Illuminate\Http\Response
     */
     public function assign_league(Request $request )
     {
         Log::info(print_r($request->input(), true));
         // get data
         $team_id = $request->input('team_id');
         $league_id = $request->input('league_id');
         $club_id = $request->input('club_id');

         Team::where('id', $team_id)->update(array('league_id' => $league_id));

         return redirect()->route('club.dashboard', ['language'=>app()->getLocale(), 'id' => $club_id ]);
     }

     /**
      * DeAttach team from league
      *
      * @param  \App\League  $league
      * @return \Illuminate\Http\Response
      */
      public function deassign_league(Request $request  )
      {
          Log::info(print_r($request->input(), true));
          // get data
          $team_id = $request->input('team_id');
          $league_id = $request->input('league_id');
          $club_id = $request->input('club_id');

          Team::where('id', $team_id)->update( ['league_id' => null, 'league_no' => null, 'league_char' => null ]);
          $check = League::find($league_id)->clubs()->wherePivot('club_id','=',$club_id)->detach();

          return Response::json(true);
      }

     /**
      * Display a dashboard
      *
      * @return \Illuminate\Http\Response
      */
     public function plan_leagues( $language, $club )
     {
        $data['club'] =  Club::find(intval($club));
        $data['teams'] = $data['club']->teams()->whereNotNull('league_id')->with('league.schedule')->get();

        return view('team/teamleague_dashboard', $data);
      }

      public function list_pivot( Request $request )
      {
        Log::info(print_r($request->all(), true));

        $select = "select date_format(se.game_date, '%a %d.%b.%Y') as 'Game Date' ";
        $where =  array();
        $cols = array();

        foreach ($request->all() as $key => $league_no) {
          if ( strpos($key, 'sel') !== false ){
            $league = explode(':', $key)[1];

            $cols[] = 'max(case when (l.id = '.$league.' and lts.team_home = '.$league_no.') then se.game_day else " " end) as "l'.$league.'"';
            $where[] = $league;
          }
        }

        $select .= ', '.implode(' ,', $cols).' FROM league_team_schemes lts, schedule_events se  , leagues l , schedules s ';
        $select .= ' WHERE l.id in ('.implode(' ,', $where).') ';
        $select .= ' AND lts.size = s.size AND s.id = l.schedule_id AND se.schedule_id = l.schedule_id AND se.game_day = lts.game_day';
        $select .= ' GROUP BY se.game_date';

        Log::debug($select);

        $plan = collect(DB::select($select));
        Log::debug(print_r($plan, true));
        $returnhtml =  view("team/teamleague_pivot", ["plan" => $plan])->render();
        // Log::debug(print_r($returnhtml, true));
        return Response::json($returnhtml);

       }

       public function list_chart( Request $request )
       {
         Log::info(print_r($request->all(), true));

         $select = "select date_format(se.game_date, '%b-%d-%Y') AS 'gamedate', count(*) as 'homegames' ";
         $where =  array();
         $cols = array();

         foreach ($request->all() as $key => $league_no) {
           if ( strpos($key, 'sel') !== false ){
             $league = explode(':', $key)[1];

             $where[] = '(l.id = '.$league.' AND lts.team_home = '.$league_no.')';
           }
         }

         $select .= ' FROM league_team_schemes lts, schedule_events se  , leagues l , schedules s ';
         $select .= ' WHERE ('.implode(' OR ', $where).') ';
         $select .= ' AND lts.size = s.size AND s.id = l.schedule_id AND se.schedule_id = l.schedule_id AND se.game_day = lts.game_day';
         $select .= " GROUP BY date_format(se.game_date, '%b-%d-%Y')";

         // Log::debug($select);
         $plan = collect(DB::select($select));
         // Log::debug(print_r($plan, true));

         return Response::json($plan);

        }



        public function Stand_Deviation($arr)
        {
            $num_of_elements = count($arr);

            $variance = 0.0;

                    // calculating mean using array_sum() method
            $average = array_sum($arr)/$num_of_elements;

            foreach($arr as $i)
            {
                // sum of squares of differences between
                            // all numbers and means.
                $variance += pow(($i - $average), 2);
            }

            return (float)sqrt($variance/$num_of_elements);
        }

        public function Cartesian_Product($data)
        {
          ini_set('memory_limit', '2G');

          // get total
          $dim=1;
          foreach($data as $vec){
            $dim = $dim * count($vec);
          };
          Log::debug('dimension is: '.$dim);
          // limit to less than 100.000 combinations
          while($dim > 200000){
            $dim =1;
            for($i=0; $i < count($data); $i++){
              if (count($data[$i]) >2){
                array_pop( $data[$i] );
              };
              $dim = $dim * count($data[$i]);
            }
          }
          Log::debug('dimension is now: '.$dim);

          $result = array(array());

          foreach ($data as $key => $values) {
              $append = array();

              foreach($result as $product) {
                  foreach($values as $item) {
                      $product[$key] = $item;
                      $append[] = $product;
                  }
              }

              $result = $append;

          }
          return $result;
        }

        public function Cartesian_Product_sql($data){
          ini_set('memory_limit', '2G');
          $dim = count($data);
          $cidx = range('A','Z');

          $col = array();
          $join = array();
          $where = array();

          foreach ( $data as $idx => $size ){
            $col[] = $cidx[$idx].".team_char AS '".$idx."' ";
            $join[] = 'league_team_chars as '.$cidx[$idx];
            $where[] = $cidx[$idx].'.size = '.count($size);
          };

          $sel = "SELECT ".implode(', ',$col);
          $sel .= " FROM ".implode(' CROSS JOIN ',$join);
          $sel .= " WHERE ".implode(' AND ',$where)." LIMIT 100000";

          // SELECT a.team_char, b.team_char,c.team_char,d.team_char,e.team_char FROM league_team_chars as a CROSS JOIN league_team_chars as b CROSS JOIN league_team_chars as c CROSS JOIN league_team_chars as d CROSS JOIN league_team_chars as e
          // WHERE a.size =4 and b.size=6 and c.size=8 and d.size=10 and e.size=12
          Log::debug($sel);
          $res = DB::select($sel);

          return $res;

        }

        /**
         * optimization for home games
         *
         * @return \Illuminate\Http\Response
         */
        public function propose_combination( Request $request )
        {
          Log::debug(print_r($request->input(), true));
          Log::info('starting');
          // THIS WORKS ! but takes long if more than 4 leagues !!!!  combinations for 5 league are 100.000 !!!
          // i.e. this takes 100.000  complex SQL calls !!! -> need to move to handle this in php
          $leagues = array();
          $leagues['id'] = array();
          $leagues['team_no'] = array();
          $leagues['schedule_id'] = array();
          $leagues['size'] = array();

          foreach ($request->all() as $key => $league_no) {
            if ( strpos($key, 'sel') !== false ){
              $league_id = explode(':', $key)[1];
              $schedule_id = League::select('schedule_id')->where('id', $league_id)->value('schedule_id');

              $leagues['id'][] = $league_id;
              $leagues['team_no'][] = $league_no;
              $leagues['schedule_id'][] = $schedule_id;
              $size = Schedule::select('size')->where('id', $schedule_id)->value('size');
              $leagues['size'][] = range(1, $size);
            }
          }
          Log::info('ref-data loaded - '.print_r(count($request->input())-4,true));
          //Log::debug(print_r($leagues, true));

          // create an array with all combinations of team numbers

          $data = $leagues['size'];

          $combinations = $this->Cartesian_Product($data);
          Log::info('combinations defined - '.count($combinations));
          //Log::debug(print_r($combinations, true));

          $sel = "SELECT date(se.game_date) as gdate, l.id as glid, lts.team_home as ghome ";
          $sel .= " FROM schedule_events se, schedules s, league_team_schemes lts, leagues l ";
          $sel .= "WHERE se.schedule_id = s.id AND lts.size = s.size AND lts.game_day = se.game_day AND l.schedule_id = s.id  ";
          $sel .= "AND l.id in (".implode(',', $leagues['id']).")";

          $filtercomb = DB::select($sel);
          Log::info('game days loaded and filtered - '.print_r(count($filtercomb),true));

          // now get averages
          $hdays_a = array();
          $hdays_s = array();

          for ($i = 0; $i < count($combinations); $i++) {
            $homies = $filtercomb;
            for ($j = 0; $j < count($leagues['id']); $j++ ) {
              $homies = array_filter($homies, function ($v) use ($leagues, $combinations,$i, $j) {
                  if ($v->glid == $leagues['id'][$j] ) {
                    if ($v->ghome == $combinations[$i][$j])
                      { return true; } else { return false; };
                  } else { return true; };
                });
            }
            //Log::debug(print_r($homies,true));
            $gdays = array_count_values(array_column($homies, 'gdate'));
          //  Log::debug(print_r($gdays,true));
            $hdays_a[$i] = array_sum($gdays)/count($gdays);
            $hdays_s[$i] = $this->Stand_Deviation($gdays);
            //Log::info('avg is :'.$avgday);
          }
          Log::debug('stats done - '.print_r(count($hdays_a),true));

          //get the combinations for min/max games/day


          $min_keys = array_slice(array_keys($hdays_a,min($hdays_a)),0,10,true);
          $min_c = array_intersect_key($combinations, array_flip($min_keys));
          $resp['c_min'] = array_values($min_c);

          $max_keys = array_slice(array_keys($hdays_a,max($hdays_a)),0,10,true);
          $max_c = array_intersect_key($combinations, array_flip($max_keys));
          $resp['c_max'] = array_values($max_c);

          // get the avergade variance
          $avgvar = array_sum($hdays_s)/count($hdays_s);

          // do days 1-5 by default
          for ($i=1; $i <= 5; $i++){
            // get values closest to an exact avg
            $remain = array_filter($hdays_a, function ($v, $k) use ($i, $hdays_s, $avgvar) {
                if (( $v < $i + $hdays_s[$k] ) and ( $v > $i - $hdays_s[$k] ) and ( $hdays_s[$k] < $avgvar ))
                  { return true; } else { return false; };
            }, ARRAY_FILTER_USE_BOTH);
            $keys = array_slice(array_keys($remain),0,10,true);
            $c = array_intersect_key($combinations, array_flip($keys));
            $resp['c_day'][$i] = array_values($c);
          }

          // get values closest to an exact avg
          $gperday = $request->input('gperday');
          if ( $gperday > 5){
            $remain = array_filter($hdays_a, function ($v, $k) use ($gperday, $hdays_s, $avgvar) {
                if (( $v < $gperday+$hdays_s[$k] ) and ( $v > $gperday-$hdays_s[$k] ) and ( $hdays_s[$k] < $avgvar))
                  { return true; } else { return false; };
            }, ARRAY_FILTER_USE_BOTH);
            $keys = array_slice(array_keys($remain),0,10,true);
            $c = array_intersect_key($combinations, array_flip($keys));
            $resp['c_day'][$gperday] = array_values($c);
          }

          $resp['leagues'] = $leagues['id'];

          Log::debug('response build ');
          return Response::json($resp);
          }


}
