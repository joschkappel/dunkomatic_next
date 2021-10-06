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
use App\Enums\LeagueState;
use App\Enums\LeagueStateChange;
use Bouncer;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Datatables;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

use App\Notifications\ClubDeAssigned;
use App\View\Components\LeagueStatus;

use App\Traits\GameManager;

class LeagueController extends Controller
{
    use GameManager;

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    return view('league/league_list');
  }

  /**
   * Display a listing of the resource .
   *
   * @return \Illuminate\Http\Response
   */
  public function list($language, Region $region)
  {

    app()->setLocale($language);

    if ( $region->is_base_level ){
        $leagues = League::whereIn('region_id', [ $region->id, $region->parentRegion->id ] )->with('schedule.league_size')
                        ->withCount([
                            'clubs', 'teams', 'registered_teams','selected_teams','games',
                            'games_notime', 'games_noshow'
                        ])
                        ->orderBy('shortname','ASC')
                        ->get();
    } else {
        $leagues = League::where('region_id', $region->id)->with('schedule.league_size')
                        ->withCount([
                            'clubs', 'teams', 'registered_teams','selected_teams','games',
                            'games_notime', 'games_noshow'
                        ])
                        ->orderBy('shortname','ASC')
                        ->get();
    }

    //Log::debug(print_r($leagues,true));

    $leaguelist = datatables()::of($leagues);

    return $leaguelist
      ->addIndexColumn()
      ->rawColumns(['shortname.display','age_type.display','gender_type.display',
                    'size.display','state'])
      ->editColumn('shortname', function ($l) use ($region) {
        if (  ( (Bouncer::can('manage', $l))
             or (Auth::user()->isA('regionadmin')) )
             and ( $l->region->is( $region ) ))
        {
          $link = '<a href="' . route('league.dashboard', ['language' => Auth::user()->locale, 'league' => $l->id]) . '" >' . $l->shortname . '</a>';
        } else {
          $link = '<a href="' . route('league.briefing', ['language' => Auth::user()->locale, 'league' => $l->id]) . '" class="text-info">' . $l->shortname . '</a>';
        }
        return array('display' =>$link, 'sort'=>$l->shortname);
      })
      ->editColumn('age_type', function ($l) {
        return array('display'=>LeagueAgeType::getDescription($l->age_type), 'sort'=>$l->age_type);
      })
      ->editColumn('gender_type', function ($l) {
        return array('display'=>LeagueGenderType::getDescription($l->gender_type), 'sort'=>$l->gender_type);
      })
      ->addColumn('size', function ($l){
        if ($l->schedule()->exists()){
          return ($l->size == null  ) ? array('display' =>null, 'sort'=>0) : array('display' =>$l->size, 'sort'=>$l->size);
        } else {
          return array('display' =>null, 'sort'=>0);
        }
      })
      ->addColumn('alien_region', function ($l) use ($region) {
          if ($region->is_base_level and $l->region->is_top_level){
              return $l->region->code;
          } else {
              return '';
          }

      })
      ->editColumn('updated_at', function ($l) {
        return ($l->updated_at==null) ? null : $l->updated_at->format('d.m.Y H:i');
      })
      ->editColumn('state', function ($l) {
            $content = new LeagueStatus($l);
            return $content->render()->with($content->data());
      })
      ->make(true);
  }


  /**
   * Display a listing of the resource for selectboxes. clubs for league
   *
   * @return \Illuminate\Http\Response
   */
  public function sb_club(League $league)
  {
    //Log::debug(print_r($club,true));

    $clubs = League::find($league->id)->region->clubs()->orderBy('shortname', 'ASC')->get();
    $leagueclubs = $league->clubs()->pluck('id');

    Log::debug('got clubs ' . count($clubs));
    $response = array();

    foreach ($clubs as $c) {
      if ($leagueclubs->contains($c->id)){
        $selected = true;
      } else {
        $selected = false;
      }

      $response[] = array(
        "id" => $c->id,
        "text" => $c->shortname,
        "selected" => $selected
      );
    }
    return Response::json($response);
  }

  /**
   * Display a listing of the resource for selectboxes.
   *
   * @return \Illuminate\Http\Response
   */
  public function sb_region(Region $region)
  {

    $leagues = $region->leagues()->orderBy('shortname', 'ASC')->get();

    Log::debug('got leagues ' . count($leagues));
    $response = array();

    foreach ($leagues as $league) {
      $response[] = array(
        "id" => $league->id,
        "text" => $league->shortname
      );
    }
    return Response::json($response);
  }

  public function sb_freechars(League $league)
  {
    $size = $league->size;
    $chars = config('dunkomatic.league_team_chars');
    $all_chars = array_slice(array_values($chars), 0, $size, true);
    // Log::debug(print_r($all_chars,true));

    $team_chars = $league->teams()->pluck('league_char')->toArray();
    // Log::debug(print_r($team_chars,true));

    $freechars = array_diff($all_chars, $team_chars);
    // Log::debug(print_r($freechars,true));

    foreach ($freechars as $key => $value) {
      $response[] = array(
        "id" => $key + 1,
        "text" => ($key + 1) . ' - ' . $value
      );
    }
    Log::debug(print_r($response, true));
    return Response::json($response);
  }


  /**
   * Display a dashboard
   *
   * @return \Illuminate\Http\Response
   */
  public function dashboard($language, League $league)
  {
    $data['league'] = $league;

    // get assigned clubs
    $clubs = $league->clubs()->get()->sortBy('shortname');
    // get assigned Teams
    $teams = $league->teams()->with('club')->get();

    $data['clubs'] = $clubs;
    $data['members'] = Member::whereIn('id', League::find($league->id)->members()->pluck('member_id'))->with('memberships')->get();

    $selected_teams = collect();
    foreach ($teams as $i => $team) {
      $selected_teams[$team->league_no] = array(
        "team_id" => $team->id,
        "shortname" => $team['club']->shortname,
        "team_no" => $team->team_no,
        "league_char" => $team->league_char,
        "league_no" => $team->league_no
      );
    }

    $assigned_club = collect();
    foreach ($clubs as $i => $club) {
      //Log::debug(print_r($club['pivot'],true));
        $assigned_club[$i+1] = array(
          "club_id" => $club->id,
          "shortname" => $club->shortname,
          "league_id" => $league->id,
          "team_registered" => false, // $league->teams()->with('club')->get()->pluck('club.shortname')->contains($club->shortname),
          "team_selected" => false, // $league->teams()->whereNotNull('league_no')->with('club')->get()->pluck('club.shortname')->contains($club->shortname)
        );
    }

    // mark club as registered or selected
    $st = $selected_teams->collect();
    $assigned_club->transform(function ($item) use (&$st) {
        $k = $st->search(function ($t) use ($item) {
          return ($t['shortname'] == $item['shortname']);
        });

        if ( $k !== false ) {
          $item['team_registered'] = true;
          if ($st[$k]['league_no'] != null){
            $item['team_selected'] = true;
          }
          $st->pull($k);
        }
        return $item;
    });

    $data['assigned_clubs'] = $assigned_club;
    $data['games'] = $data['league']->games()->get();
    $data['selected_teams'] = $selected_teams;
    //Log::debug(print_r($assigned_team,true));
    $directory =   $directory = session('cur_region')->league_folder;
    $reports = collect(Storage::allFiles($directory))->filter(function ($value, $key) use ($league) {
      return (strpos($value, $league->shortname) !== false);
    });

    //Log::debug(print_r($reports,true));
    $data['files'] = $reports;

    return view('league/league_dashboard', $data);
  }

  /**
   * Display a brief overview
   *
   * @return \Illuminate\Http\Response
   */
  public function briefing($language, League $league)
  {
    $data['league'] = $league;

    // get assigned clubs
    $clubs = $league->clubs()->with('memberships')->get()->sortBy('shortname');
    // get assigned Teams
    $teams = $league->teams()->with('club')->get();

    $data['clubs'] = $clubs;
    $data['memberships'] = $league->memberships()->with('member')->get();
    $data['teams'] = $teams;

    return view('league/league_briefing', $data);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    Log::info('create new league');
    return view(
      'league/league_new',
      [
        'region' => session('cur_region'),
        'agetype' => LeagueAgeType::getInstances(),
        'gendertype' => LeagueGenderType::getInstances()
      ]
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
    $data = $request->validate([
      'shortname' => array(
        'required',
        'string',
        'unique:leagues',
        'max:10'
      ),
      'league_size_id' => 'required|exists:league_sizes,id',
      'schedule_id' => 'required|exists:schedules,id',
      'name' => 'required|max:255',
      'region_id' => 'required|exists:regions,id',
      'age_type' => ['required', new EnumValue(LeagueAgeType::class, false)],
      'gender_type' => ['required', new EnumValue(LeagueGenderType::class, false)],

    ]);

    $above_region = $request->input('above_region');
    if (isset($above_region) and ($above_region === 'on')) {
      $data['above_region'] = True;
    } else {
      $data['above_region'] = False;
    }
    Log::debug(print_r($data, true));

    $check = League::create($data);
    return redirect()->route('league.index', app()->getLocale());
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
    Log::debug('editing league ' . $league->id);
    $member = $league->memberships()->with('member')->first();
    if (isset($member)) {
      $rmember = $member->member;
    } else {
      $rmember = null;
    }
    Log::debug(print_r($rmember, true));
    return view('league/league_edit', [
      'league' => $league,
      'member' => $rmember,
      'agetype' => LeagueAgeType::getInstances(),
      'gendertype' => LeagueGenderType::getInstances()
    ]);
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
    $data = $request->validate([
      'shortname' => array(
        'required',
        'string',
        Rule::unique('leagues')->ignore($league->id),
        'max:10'
      ),
      'schedule_id' => 'required|exists:schedules,id',
      'league_size_id' => 'required|exists:league_sizes,id',
      'name' => 'required|max:255',
      'age_type' => ['required', new EnumValue(LeagueAgeType::class, false)],
      'gender_type' => ['required', new EnumValue(LeagueGenderType::class, false)],
    ]);

    $above_region = $request->input('above_region');
    if (isset($above_region) and ($above_region == 'on')) {
      $data['above_region'] = True;
    } else {
      $data['above_region'] = False;
    }

    Log::debug('ready to update league:' . print_r($data, true));
    $result = $league->update($data);
    return redirect()->route('league.dashboard', ['language' => app()->getLocale(), 'league' => $league]);
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
    foreach ($league->teams as $t) {
      $t->league()->dissociate();
      $t->league_char = null;
      $t->league_no = null;
      $t->save();
    }
    $mships = $league->memberships()->get();
    foreach ($mships as $ms) {
      $ms->delete();
    }

    $league->delete();

    return redirect()->route('league.index', ['language' => app()->getLocale()]);
  }

  /**
   * Detach club from league
   *
   * @param  \App\Models\League  $league
   * @return \Illuminate\Http\Response
   */
  public function deassign_club(Request $request, League $league, Club $club)
  {
    $upperArr = config('dunkomatic.league_team_chars');
    // special treatment as values might be duplicate
    $occurences = $league->clubs->pluck('id')->intersect([$club->id])->count();
    if ( $occurences > 1){
      $assigned_clubs = $league->clubs->pluck('id')->diff([$club->id]);
      for ($i=1; $i<$occurences; $i++){
        $assigned_clubs[] = $club->id;
      }
      $league->clubs()->detach();
      foreach ($assigned_clubs as $i => $ac){
        $c = $upperArr[$i+1];
        $league->clubs()->attach( [$ac => ['league_no' => $i+1, 'league_char' => $c]]);
      }
    } else {
      $league->clubs()->detach($club);
    }


    // deassign teams as well
    $team = Team::where('club_id', $club->id)->where('league_id', $league->id)->first();
    if (isset($team)) {
      $team->update(['league_id' => null, 'league_no' => null, 'league_char' => null]);

      // if league games are generated, delete these games as well
      $this->blank_team_games($league, $team);
    }


    $member = $club->members()->wherePivot('role_id', Role::ClubLead)->first();

    if ((isset($member)) and (isset($team))) {
      $member->notify(new ClubDeAssigned($league, $club, $team, Auth::user()->name, $member->name));
      $user = $member->user;
      if (isset($user)) {
        $user->notify(new ClubDeAssigned($league, $club, $team, Auth::user()->name, $user->name));
      }
    }

    return Response::json('OK');
  }

  /**
   * Attach club to league
   *
   * @param  \App\Models\League  $league
   * @return \Illuminate\Http\Response
   */
  public function assign_clubs(Request $request, League $league)
  {

    $data = $request->validate([
      'assignedClubs' => ['nullable', 'array', 'min:0', 'max:'.$league->size ],
      'assignedClubs.*' => 'nullable|exists:clubs,id',
      'club_id' => 'nullable|exists:clubs,id',
    ]);

    $upperArr = config('dunkomatic.league_team_chars');

    // assign new list
    if (isset($data['assignedClubs'])){
      // delete old entries
      $league->clubs()->detach();

      foreach ($data['assignedClubs'] as $i => $c){
        $league_no = $i+1;
        $league_char = $upperArr[$league_no];
        Log::debug('league_char: ' . $league_char);

        $league->clubs()->attach(
            $c,
            [
              'league_no' => $league_no,
              'league_char' => $league_char
            ]
          );
      }
    } elseif (isset($data['club_id'])){
      $league_no = $league->clubs->max('pivot.league_no') +1;
      $league_char = $upperArr[$league_no];
      $league->clubs()->attach(
        Club::find($data['club_id']),
        [
          'league_no' => $league_no,
          'league_char' => $league_char
        ]
      );
    }

    return redirect()->back();
    //route('league.dashboard', ['language' => app()->getLocale(), 'league' => $league]);
  }


  /**
   * display management dashboard
   *
   */
  public function mgmt_dashboard()
  {
    return view('league.league_management');
  }
  /**
   * league datatables club assignments
   *
   */
  public function club_assign_dt($language, Region $region)
  {

    if ( $region->is_base_level ){
        $leagues = League::whereIn('region_id', [ $region->id, $region->parentRegion->id ] )->get();
    } else {
        $leagues = $region->leagues;
    }


    //Log::debug(print_r($leagues,true));

    $leaguelist = datatables()::of($leagues);
    app()->setLocale($language);

    return $leaguelist
      ->addIndexColumn()
      ->rawColumns(['shortname.display','nextaction','rollbackaction','clubs','teams',
                    'state'])
      ->editColumn('shortname', function ($l) use ($region) {
        if (  ( (Bouncer::can('manage', $l))
             or (Auth::user()->isA('regionadmin')) )
             and ( $l->region->is( $region ) )
            ) {
          $link = '<a href="' . route('league.dashboard', ['language' => Auth::user()->locale, 'league' => $l->id]) . '" >' . $l->shortname . '</a>';
        } else {
          $link = '<a href="' . route('league.briefing', ['language' => Auth::user()->locale, 'league' => $l->id]) . '" class="text-info">' . $l->shortname . '</a>';
        }
        return array('display' =>$link, 'sort'=>$l->shortname);
      })
      ->addColumn('alien_region', function ($l) use ($region) {
        if ($region->is_base_level and $l->region->is_top_level){
            return $l->region->code;
        } else {
            return '';
        }

        })
      ->addColumn('nextaction', function ($data) use ($region) {
          $btn = '';
          if ($region->is($data->region)){
            if ($data->state->is( LeagueState::Assignment())){
                $btn = '<button type="button" class="btn btn-primary btn-sm" id="changeState" data-league="'.$data->id.'"
                        data-action="'.LeagueStateChange::CloseAssignment().'"><i class="fas fa-lock"> </i> '.__('league.action.close.assignment').'</button>';
                $btn .= '<button type="button" class="btn btn-secondary btn-sm" id="assignClub" data-league="'.$data->id.'"
                        data-toggle="collapse" data-target="#collapseAssignment"><i class="fas fa-lock"> </i> Assign Clubs</button>';
            } elseif ($data->state->is( LeagueState::Registration())) {
                $btn = '<button type="button" class="btn btn-primary btn-sm" id="changeState" data-league="'.$data->id.'"
                        data-action="'.LeagueStateChange::CloseRegistration().'"><i class="fas fa-lock"> </i> '.__('league.action.close.registration').'</button>';
            } elseif ($data->state->is( LeagueState::Selection())) {
                $btn = '<button type="button" class="btn btn-primary btn-sm" id="changeState" data-league="'.$data->id.'"
                        data-action="'.LeagueStateChange::CloseSelection().'"><i class="fas fa-lock"> </i> '.__('league.action.close.selection').'</button>';
            } elseif ($data->state->is( LeagueState::Freeze())) {
                $btn = '<button type="button" class="btn btn-primary btn-sm" id="createGames" data-league="'.$data->id.'"><i class="fas fa-plus-circle"> </i> '. __('league.action.close.freeze').'
                        </button>';
            } elseif ($data->state->is( LeagueState::Scheduling())) {
                $btn = '<button type="button" class="btn btn-primary btn-sm" id="changeState" data-league="'.$data->id.'"
                        data-action="'.LeagueStateChange::CloseScheduling().'"><i class="fas fa-lock"> </i> '.__('league.action.close.scheduling').'</button>';
            } elseif ($data->state->is( LeagueState::Referees())) {
                $btn = '<button type="button" class="btn btn-primary btn-sm" id="changeState" data-league="'.$data->id.'"
                        data-action="'.LeagueStateChange::CloseReferees().'"><i class="fas fa-lock"> </i> '.__('league.action.close.referees').'</button>';
            }
        }
        return $btn;
      })
      ->addColumn('rollbackaction', function ($data) use ($region) {
          $btn = '';
          if ($region->is($data->region)){
            if ($data->state->is( LeagueState::Registration())) {
                $btn .= '<button type="button" class="btn btn-outline-danger btn-sm" id="changeState" data-league="'.$data->id.'"
                        data-action="'.LeagueStateChange::OpenAssignment().'"><i class="fas fa-lock"> </i> '.__('league.action.open.assignment').'</button>';
            } elseif ($data->state->is( LeagueState::Selection())) {
                $btn .= '<button type="button" class="btn btn-outline-danger btn-sm" id="changeState" data-league="'.$data->id.'"
                        data-action="'.LeagueStateChange::OpenRegistration().'"><i class="fas fa-lock"> </i> '.__('league.action.open.registration').'</button>';
            } elseif ($data->state->is( LeagueState::Freeze())) {
                $btn .= '<button type="button" class="btn btn-outline-danger btn-sm" id="changeState" data-league="'.$data->id.'"
                        data-action="'.LeagueStateChange::OpenSelection().'"><i class="fas fa-lock"> </i> '.__('league.action.open.selection').'</button>';
            } elseif ($data->state->is( LeagueState::Scheduling())) {
                $btn .= '<button type="button" class="btn btn-outline-danger btn-sm" id="deleteGames" data-league="'.$data->id.'"><i class="fas fa-minus-circle"> </i> '. __('league.action.open.freeze').'
                        </button>';
            } elseif ($data->state->is( LeagueState::Referees())) {
                $btn .= '<button type="button" class="btn btn-outline-danger btn-sm" id="changeState" data-league="'.$data->id.'"
                        data-action="'.LeagueStateChange::OpenScheduling().'"><i class="fas fa-lock"> </i> '. __('league.action.open.scheduling').'</button>';
            } elseif ($data->state->is( LeagueState::Live())) {
                $btn .= '<button type="button" class="btn btn-outline-danger btn-sm" id="changeState" data-league="'.$data->id.'"
                        data-action="'.LeagueStateChange::OpenReferees().'"><i class="fas fa-lock"> </i> '.__('league.action.open.referees').'</button>';
            }
         }
         return $btn;
      })
      ->addColumn('clubs', function ($data) {
        $btnlist = '';

        $ccnt = 1;
        $t = $data->teams()->with('club')->get()->groupBy('club.shortname');
        foreach ($data->clubs->groupBy('shortname') as $k => $c){
          if ($t->get($k) == null){
            $diff = $c->count();
          } else {
            $diff = $c->count() > $t->get($k)->count();
          }
          if (  $diff > 0 ){
            for ($i=0; $i < $diff; $i++){
              $btnlist .= '<button disabled  type="button" class="btn btn-outline-success btn-sm">'.$k.'</button> ';
            }
          };
          $ccnt += $c->count();
        }
        if ($data->state->is(LeagueState::Assignment())){
          for ($i = $ccnt; $i <= $data->size; $i++){
            $btnlist .= '<button type="button" class="btn btn-outline-warning btn-sm" >?</button> ';
          }
        }

        return $btnlist;
      })
      ->addColumn('teams', function ($l) {
        $btnlist = '';
        $ccnt = 1;
        $c = $l->clubs->pluck('shortname');
        foreach ($l->teams()->with('club')->orderBy('league_no')->get()->pluck('league_no','club.shortname') as $lt => $lnr ){
          if ($c->contains($lt)){
            if ($lnr == null){
              $clr = 'btn-warning';
            } else {
              $clr = 'btn-success';
            }
          } else {
            $clr = 'btn-danger';
          }
          $btnlist .= '<button disabled  type="button" class="btn '.$clr.' btn-sm">'.$lt.' <span class="badge badge-pill badge-light">'.$lnr.'</span></button> ';
          $ccnt += 1;
        }
        return $btnlist;
      })
      ->editColumn('state', function ($l) {
        $content = new LeagueStatus($l, 'badge');
        return $content->render()->with($content->data());
      })
      ->make(true);

  }
}
