<?php

namespace App\Http\Controllers;

use App\Models\League;
use App\Models\Region;
use App\Models\Team;
use App\Models\Club;
use App\Models\Member;


use App\Enums\LeagueAgeType;
use App\Enums\LeagueGenderType;
use App\Enums\Role;
use BenSampo\Enum\Rules\EnumValue;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Datatables;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

use App\Notifications\ClubAssigned;
use App\Notifications\ClubDeAssigned;

class LeagueController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('league/league_list');
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
    public function list_stats(Region $region)
    {

      $leagues = $region->leagues()
                         ->with('schedule.league_size')
                         ->withCount(['clubs','teams','games',
                                      'games_notime','games_noshow'])
                         ->get();

      //Log::debug(print_r($leagues,true));

      $leaguelist = datatables()::of($leagues);

      return $leaguelist
        ->addIndexColumn()
        ->rawColumns(['shortname','reg_rel'])
        ->editColumn('shortname', function ($data) {
            return '<a href="' . route('league.dashboard', ['language'=>Auth::user()->locale,'league'=>$data->id]) .'">'.$data->shortname.'</a>';
            })
        ->addColumn('reg_rel', function($data){
              if ($data->clubs_count!=0){
                $reg_rel = round(($data->teams_count * 100)/$data->clubs_count);
              } else {
                $reg_rel = 0;
              }
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
    public function list(Region $region)
    {
        //
        $leaguelist = datatables()::of($region->leagues()->with('schedule'));

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
              return '<a href="' . route('league.dashboard', ['language'=>Auth::user()->locale,'league'=>$data->id]) .'">'.$data->shortname.'</a>';
              })
          ->make(true);
    }

    /**
     * Display a listing of the resource for selectboxes.
     *
     * @return \Illuminate\Http\Response
     */
    public function sb_region(Region $region)
    {

      $leagues = $region->leagues()->orderBy('shortname','ASC')->get();

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

    public function sb_freechars(League $league)
    {
      $size = $league->schedule->size;
      $chars = config('dunkomatic.league_team_chars');
      $all_chars = array_slice( array_values($chars), 0, $size, true );
      // Log::debug(print_r($all_chars,true));

      $team_chars = $league->teams()->pluck('league_char')->toArray();
      // Log::debug(print_r($team_chars,true));

      $freechars = array_diff($all_chars,$team_chars);
      // Log::debug(print_r($freechars,true));

      foreach ($freechars as $key => $value){
        $response[] = array(
              "id"=>$key+1,
              "text"=>($key+1).' - '.$value
            );
      }
      Log::debug(print_r($response,true));
      return Response::json($response);

    }

    /**
     * Display a listing of the resource for selectboxes. leagues for club
     *
     * @return \Illuminate\Http\Response
     */
    public function sb_club(Club $club)
    {
      //Log::debug(print_r($club,true));

      $leagues = $club->leagues()->orderBy('shortname','ASC')->get();

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
    public function dashboard( $language, League $league )
    {
        $data['league'] = $league;

        // get assigned clubs
        $clubs = $league->clubs()->get();
        $data['clubs'] = $clubs;
        $data['members'] = Member::whereIn('id',League::find($league->id)->members()->pluck('member_id'))->with('memberships')->get();

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
            $assigned_team[$team->league_no] = array(
                  "team_id"=>$team->id,
                  "shortname"=>$team['club']->shortname,
                  "team_no"=>$team->team_no,
                  "league_char"=>$team->league_char,
                  "league_no"=>$team->league_no
                );
        }
        $data['assigned_teams'] = $assigned_team;
        //Log::debug(print_r($assigned_team,true));
        $directory =   $directory = session('cur_region')->league_folder;
        $reports = collect(Storage::allFiles($directory))->filter(function ($value, $key) use ($league){
          return (strpos($value,$league->shortname) !== false);
        });

        //Log::debug(print_r($reports,true));
        $data['files'] = $reports;

        return view('league/league_dashboard', $data);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      Log::info('create new league');
      return view('league/league_new', ['region' => session('cur_region'),
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
          'region_id' => 'required|max:5|exists:regions,id',
          'age_type' => ['required', new EnumValue(LeagueAgeType::class, false)],
          'gender_type' => ['required', new EnumValue(LeagueGenderType::class, false)],

      ]);

      $above_region = $request->input('above_region');
      if ( isset($above_region) and ( $above_region === 'on' )){
        $data['above_region'] = True;
      } else {
        $data['above_region'] = False;
      }

      Log::info(print_r($data, true));

      $check = League::create($data);
      return redirect()->route('league.index', app()->getLocale() );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\League  $league
     * @return \Illuminate\Http\Response
     */
    public function show(League $league)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\League  $league
     * @return \Illuminate\Http\Response
     */
    public function edit($language, League $league)
    {
      Log::debug('editing league '.$league->id);
      $member = $league->memberships()->with('member')->first();
      if (isset($member)){
        $rmember = $member->member;
      } else {
        $rmember = null;
      }
      Log::debug(print_r($rmember,true));
      return view('league/league_edit', ['league' => $league,
                                         'member' => $rmember,
                                         'agetype' => LeagueAgeType::getInstances(),
                                         'gendertype' => LeagueGenderType::getInstances()]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\League  $league
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
          'age_type' => ['required', new EnumValue(LeagueAgeType::class, false)],
          'gender_type' => ['required', new EnumValue(LeagueGenderType::class, false)],
      ]);

      $above_region = $request->input('above_region');
      if ( isset($above_region) and ( $above_region === 'on' )){
        $data['above_region'] = True;
      } else {
        $data['above_region'] = False;
      }

      Log::debug('ready to update league:'.print_r($data, true));
      $check = League::find($league->id)->update($data);
      return redirect()->route('league.dashboard',['language'=>app()->getLocale(), 'league'=>$league]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\League  $league
     * @return \Illuminate\Http\Response
     */
     public function destroy(League $league)
     {
         $league->clubs()->detach();
         $check = $league->delete();

         return Response::json($check);
     }

     /**
      * Detach club from league
      *
      * @param  \App\Models\League  $league
      * @return \Illuminate\Http\Response
      */
      public function deassign_club(Request $request, League $league, Club $club)
      {
          $check = False;

          if ($league){
            $check = $league->clubs()->detach($club->id);
            // deassign teams as well
            $team = Team::where('club_id', $club->id)->where('league_id', $league->id)->first();
            if (isset($team)){
              $check = $team->update(['league_id' => null, 'league_no' => null, 'league_char' => null]);
            }

            $member = $club->members()->wherePivot('role_id', Role::ClubLead)->first();

            if ( (isset($member)) and (isset($team)) ){
              $member->notify(new ClubDeAssigned($league, $club, $team, Auth::user()->name, $member->name ));
              $user = $member->user;
              if (isset($user)){
                $user->notify(new ClubDeAssigned($league, $club, $team, Auth::user()->name, $user->name ));
              }
            }
          }
          Log::debug(print_r(Response::json($check),true));

          return Response::json($check);
      }

      /**
       * Attach club to league
       *
       * @param  \App\Models\League  $league
       * @return \Illuminate\Http\Response
       */
       public function assign_club(Request $request, League $league )
       {
           $check = False;
           $league_no = $request->input( 'item_id');
           $upperArr = config('dunkomatic.league_team_chars');
           $league_char = $upperArr[$league_no];

           Log::debug('league_char: '.$league_char);

           if ($league){
             $check = $league->clubs()->attach($request->input('club_id'),
              ['league_no' => $league_no,
               'league_char' => $league_char ]);
              $club = Club::find($request->input('club_id'));
              $member = $club->members()->wherePivot('role_id', Role::ClubLead)->first();

              if (isset($member)){
                $member->notify(new ClubAssigned($league, $club, Auth::user()->name, $member->name ));
                $user = $member->user;
                if (isset($user)){
                  $user->notify(new ClubAssigned($league, $club, Auth::user()->name, $user->name ));
                }
              }
           }
           return redirect()->route('league.dashboard', ['language'=>app()->getLocale(), 'league' => $league ]);
       }

}
