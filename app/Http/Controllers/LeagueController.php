<?php

namespace App\Http\Controllers;

use App\League;
use App\Team;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Datatables;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class LeagueController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      if (( Auth::user()->superuser ) or ( Auth::user()->regionuser )) {
        return view('league/league_list');
      } else {

        $leaguelist = explode( ",", Auth::user()->league_ids);

        if (count($leaguelist)>1) {
          return redirect()->action(
            'LeagueController@dashboard', ['id' => $leaguelist[0]]
          );
        } else {
          return back();
        }
      }
    }

    /**
     * Display a listing of the resource .
     *
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
        //
        $region = Auth::user()->region;
        Log::debug('get leagues for region '.$region);

        if ($region == ''){
          $leaguelist = datatables::of(League::query()->with('schedule'));
        } else {
          $leaguelist = datatables::of(League::query()->where('region', '=', $region)->with('schedule'));
        }

        return $leaguelist
          ->addIndexColumn()
          ->addColumn('action', function($data){
                $btn = ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$data->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteLeague"><i class="fa fa-fw fa-trash"></i>Delete</a>';
                return $btn;
          })
          ->rawColumns(['action','shortname'])
          ->editColumn('created_at', function ($user) {
                  return $user->created_at->format('d.m.Y H:i');
              })
          ->editColumn('shortname', function ($data) {
              return '<a href="' . route('league.dashboard', $data->id) .'">'.$data->shortname.'</a>';
              })
          ->make(true);
    }

    /**
     * Display a listing of the resource for selectboxes.
     *
     * @return \Illuminate\Http\Response
     */
    public function list_select()
    {
      $user_region = array( Auth::user()->region );

      $leagues = League::query()->whereIn('region', $user_region)->orderBy('shortname','ASC')->get();

      Log::debug('got leagues '.count($leagues));
      $response = array();

      foreach($leagues as $league){
          $response[] = array(
                "id"=>$league->id,
                "text"=>$league->shortname
              );
      }
      return Response::json($response);
    }

    /**
     * Display a dashboard
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard( $id )
    {
        //

          if ( $id ){
            $league =  League::find(intval($id));
            $data['league'] = $league;

            if ($data['league']){
              // get assigned clubs
              $clubs = $league->clubs()->get();
              $data['clubs'] = $clubs;

              $assigned_club = array();
              foreach($clubs as $club){
                  //Log::debug(print_r($club['pivot'],true));
                  $assigned_club[$club['pivot']->league_no] = array(
                        "club_id"=>$club->id,
                        "shortname"=>$club->shortname,
                        "league_char"=>$club['pivot']->league_char,
                        "league_id"=>$club['pivot']->league_id
                      );
              }
              $data['assigned_clubs'] = $assigned_club;



              //Log::debug(print_r($assigned_club, true));
              //Log::debug(print_r($data['clubs'], true));

              // get assigned Teams
              $teams = $league->teams()->with('club')->get();
              //$data['teams'] = $teams;

              //Log::debug(print_r($teams,true));
              $assigned_team = array();
              foreach($teams as $team){
                  $assigned_team[$team->league_no] = array(
                        "team_id"=>$team->id,
                        "shortname"=>$team['club']->shortname,
                        "team_no"=>$team->team_no,
                        "league_char"=>$team->league_char,
                        "league_no"=>$team->league_no
                      );
              }
              $data['assigned_teams'] = $assigned_team;
              Log::debug(print_r($assigned_team,true));

              return view('league/league_dashboard', $data);
            }
          }

          return view('welcome');

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      Log::info('create new league');
      return view('league/league_new', ['region' => Auth::user()->region]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      Log::debug(print_r($request->input(), true));
      $data = $request->validate( [
          'shortname' => array(
            'required',
            'string',
            'unique:leagues',
            'max:10' ),
          'schedule_id' => 'required|exists:schedules,id',
          'name' => 'required|max:255',
          'region' => 'required|max:5|exists:regions,id'
      ]);

      $above_region = $request->input('above_region');
      if ( isset($above_region) and ( $above_region === 'on' )){
        $data['above_region'] = True;
      } else {
        $data['above_region'] = False;
      }

      $active = $request->input('active');
      if ( isset($active) and ( $active === 'on' )){
        $data['active'] = True;
      } else {
        $data['active'] = False;
      }

      Log::info(print_r($data, true));

      $check = League::create($data);
      return redirect()->action(
          'LeagueController@index')->withSuccess('New League saved  ');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\League  $league
     * @return \Illuminate\Http\Response
     */
    public function show(League $league)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\League  $league
     * @return \Illuminate\Http\Response
     */
    public function edit(League $league)
    {
      Log::debug('editing league '.$league->id);
      return view('league/league_edit', ['league' => $league]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\League  $league
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, League $league)
    {
      Log::debug(print_r($request->input(), true));
      $data = $request->validate( [
          'shortname' => array(
            'required',
            'string',
            Rule::unique('leagues')->ignore($league->id),
            'max:10' ),
          'schedule_id' => 'required|exists:schedules,id',
          'name' => 'required|max:255',
          'region' => 'required|max:5|exists:regions,id'
      ]);

      $above_region = $request->input('above_region');
      if ( isset($above_region) and ( $above_region === 'on' )){
        $data['above_region'] = True;
      } else {
        $data['above_region'] = False;
      }

      $active = $request->input('active');
      if ( isset($active) and ( $active === 'on' )){
        $data['active'] = True;
      } else {
        $data['active'] = False;
      }

      Log::debug(print_r($data, true));
      $check = league::where('id', $league->id)->update($data);
      return redirect()->action(
          'LeagueController@index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\League  $league
     * @return \Illuminate\Http\Response
     */
     public function destroy(Request $request, $id)
     {
         Log::info(print_r($id, true));

         $league = League::find($id);

         $league->clubs()->detach();
         $check = $league->delete();

         return Response::json($check);
     }

     /**
      * Detach club from league
      *
      * @param  \App\League  $league
      * @return \Illuminate\Http\Response
      */
      public function deassign_club(Request $request, $league_id, $club_id)
      {
          Log::info(print_r($club_id, true));
          Log::info(print_r($league_id, true));

          $league = League::find($league_id);
          $check = False;

          if ($league){
            $check = $league->clubs()->wherePivot('club_id','=',$club_id)->detach();
            // deassign teams as well
            $teams = Team::where('club_id', $club_id)->where('league_id', $league_id)
                    ->update(['league_id' => null, 'league_no' => null, 'league_char' => null]);
          }

          return Response::json($check);
      }

      /**
       * Attach club to league
       *
       * @param  \App\League  $league
       * @return \Illuminate\Http\Response
       */
       public function assign_club(Request $request, $league )
       {
           Log::info(print_r($league, true));
           // Log::info(print_r($request->input(), true));

           $league = League::find($league);
           $check = False;
           $league_no = $request->input( 'item_id');
           $upperArr = range('A', 'Q');
           $league_char = $upperArr[$league_no-1];

           Log::debug('league_car: '.$league_char);

           if ($league){
             $check = $league->clubs()->attach($request->input('club_id'),
              ['league_no' => $league_no,
               'league_char' => $league_char ]);

           }
           return redirect()->route('league.dashboard', ['id' => $league ]);
       }

}
