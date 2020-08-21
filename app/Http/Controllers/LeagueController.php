<?php

namespace App\Http\Controllers;

use App\League;
use App\LeagueClub;
use App\Team;
use App\Club;
use App\Game;

use App\Enums\LeagueAgeType;
use App\Enums\LeagueGenderType;
use BenSampo\Enum\Rules\EnumValue;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Datatables;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Database\Eloquent\Builder;

class LeagueController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      if ( Auth::user()->regionadmin )  {
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

    public function index_stats()
    {
      return view('league/league_stats');
    }
    /**
     * Display a listing of the resource .
     *
     * @return \Illuminate\Http\Response
     */
    public function list_stats()
    {
      //
      $region = Auth::user()->region;
      Log::debug('get leagues for region '.$region);

      if ($region == ''){
        $leagues = League::query()->with('schedule');
      } else {
        $leagues = League::query()->where('region', '=', $region)->with('schedule');
      }

      $leagues = $leagues->withCount(['clubs','teams','games',
                                     'games_notime' => function (Builder $query) {
                                          $query->whereNull('game_time');},
                                     'games_noshow' => function (Builder $query) {
                                          $query->whereNull('team_id_home')->orWhereNull('team_id_guest');},
                          ])
                        ->get();

      //Log::debug(print_r($leagues,true));

      $leaguelist = datatables::of($leagues);

      return $leaguelist
        ->addIndexColumn()
        ->rawColumns(['shortname','reg_rel'])
        ->editColumn('shortname', function ($data) {
            return '<a href="' . route('league.dashboard', ['language'=>app()->getLocale(),'id'=>$data->id]) .'">'.$data->shortname.'</a>';
            })
        ->addColumn('reg_rel', function($data){
              $reg_rel = round(($data->teams_count * 100)/$data->clubs_count);
              if ($reg_rel >= 100){
                return '<div class="bg-success text-center">'.$reg_rel.'</div>';
              } else if ($reg_rel <= 50){
                return '<div class="bg-danger text-center">'.$reg_rel.'</div>';
              } else {
                return '<div class="bg-warning text-center">'.$reg_rel.'</div>';
              }
        })
        ->make(true);

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
          // ->addColumn('action', function($data){
          //       $btn = ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$data->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteLeague"><i class="fa fa-fw fa-trash"></i>'.__('league.action.delete').'</a>';
          //       return $btn;
          // })
          ->rawColumns(['shortname'])
          ->editColumn('created_at', function ($user) {
                  return $user->created_at->format('d.m.Y H:i');
              })
          ->editColumn('shortname', function ($data) {
              return '<a href="' . route('league.dashboard', ['language'=>app()->getLocale(),'id'=>$data->id]) .'">'.$data->shortname.'</a>';
              })
          ->make(true);
    }

    /**
     * Display a listing of the resource for selectboxes. leagues for club
     *
     * @return \Illuminate\Http\Response
     */
    public function list_select4club(Club $club)
    {
      Log::debug(print_r($club,true));

      $leagues = League::whereHas('clubs', function($q) use ($club)
                { $q->where('club_id', '=', $club->id);
                })->get();

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
    public function dashboard( $language, $id )
    {
        //

          if ( $id ){
            $league =  League::find(intval($id));
            $data['league'] = $league;

            if ($data['league']){
              // get assigned clubs
              $clubs = $league->clubs()->get();
              $data['clubs'] = $clubs;
              $data['member_roles'] = $data['league']->member_roles()->with('role','member')->get();

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
              $data['games'] = $data['league']->games()->get();


              //Log::debug(print_r($assigned_club, true));
              //Log::debug(print_r($data['clubs'], true));

              // get assigned Teams
              $teams = $league->teams()->with('club')->get();
              //$data['teams'] = $teams;

              //Log::debug(print_r($teams,true));
              $assigned_team = array();
              foreach($teams as $team){
                  $assigned_team[$team->league_char] = array(
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
      return view('league/league_new', ['region' => Auth::user()->region,
                                        'agetype' => LeagueAgeType::getInstances(),
                                        'gendertype' => LeagueGenderType::getInstances()]
                                      );
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
          'region' => 'required|max:5|exists:regions,code',
          'age_type' => ['required', new EnumValue(LeagueAgeType::class, false)],
          'gender_type' => ['required', new EnumValue(LeagueGenderType::class, false)],

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
      return redirect()->route('league.index', app()->getLocale() );
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
    public function edit($language, League $league)
    {
      Log::debug('editing league '.$league->id);
      $member = $league->member_roles()->with('member')->get();
//      Log::debug(print_r($member[0]['member'],true));
      return view('league/league_edit', ['league' => $league,
                                         'member' => $member[0]->member,
                                         'agetype' => LeagueAgeType::getInstances(),
                                         'gendertype' => LeagueGenderType::getInstances()]);
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
          'region' => 'required|max:5|exists:regions,code',
          'age_type' => ['required', new EnumValue(LeagueAgeType::class, false)],
          'gender_type' => ['required', new EnumValue(LeagueGenderType::class, false)],
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
      return redirect()->route('league.dashboard',['language'=>app()->getLocale(), 'id'=>$league]);
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
           return redirect()->route('league.dashboard', ['language'=>app()->getLocale(), 'id' => $league ]);
       }

}
