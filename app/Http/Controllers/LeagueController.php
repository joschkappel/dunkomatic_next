<?php

namespace App\Http\Controllers;

use App\Models\League;
use App\Models\Region;
use App\Models\Club;
use App\Models\Member;


use App\Enums\LeagueAgeType;
use App\Enums\LeagueGenderType;
use BenSampo\Enum\Rules\EnumValue;
use App\Enums\LeagueState;
use App\Enums\LeagueStateChange;
use Silber\Bouncer\BouncerFacade as Bouncer;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Datatables;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

use App\View\Components\LeagueStatus;

use App\Traits\GameManager;

class LeagueController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($language, Region $region)
    {
        Log::info('showing league list');
        return view('league/league_list', ['region' => $region]);
    }

    /**
     * Display a listing of the resource .
     *
     * @return \Illuminate\Http\Response
     */
    public function list($language, Region $region)
    {

        app()->setLocale($language);

        if ($region->is_base_level) {
            Log::notice('getting leagues for top level region');
            $leagues = League::whereIn('region_id', [$region->id, $region->parentRegion->id])->with('schedule.league_size')
                ->withCount([
                    'clubs', 'teams', 'registered_teams', 'selected_teams', 'games',
                    'games_notime', 'games_noshow'
                ])
                ->orderBy('shortname', 'ASC')
                ->get();
        } else {
            Log::notice('getting leagues for base level region');
            $leagues = League::where('region_id', $region->id)->with('schedule.league_size')
                ->withCount([
                    'clubs', 'teams', 'registered_teams', 'selected_teams', 'games',
                    'games_notime', 'games_noshow'
                ])
                ->orderBy('shortname', 'ASC')
                ->get();
        }

        //Log::debug(print_r($leagues,true));
        Log::info('preparing league list');
        $leaguelist = datatables()::of($leagues);

        return $leaguelist
            ->addIndexColumn()
            ->rawColumns([
                'shortname.display', 'age_type.display', 'gender_type.display',
                'size.display', 'state'
            ])
            ->editColumn('shortname', function ($l) {
                if (Bouncer::canAny(['create-leagues', 'update-leagues'])) {
                    $link = '<a href="' . route('league.dashboard', ['language' => Auth::user()->locale, 'league' => $l->id]) . '" >' . $l->shortname . '</a>';
                } else {
                    $link = '<a href="' . route('league.briefing', ['language' => Auth::user()->locale, 'league' => $l->id]) . '" class="text-info">' . $l->shortname . '</a>';
                }
                return array('display' => $link, 'sort' => $l->shortname);
            })
            ->editColumn('age_type', function ($l) {
                return array('display' => LeagueAgeType::getDescription($l->age_type), 'sort' => $l->age_type);
            })
            ->editColumn('gender_type', function ($l) {
                return array('display' => LeagueGenderType::getDescription($l->gender_type), 'sort' => $l->gender_type);
            })
            ->addColumn('size', function ($l) {
                if ($l->schedule()->exists()) {
                    return ($l->size == null) ? array('display' => null, 'sort' => 0) : array('display' => $l->size, 'sort' => $l->size);
                } else {
                    return array('display' => null, 'sort' => 0);
                }
            })
            ->addColumn('alien_region', function ($l) use ($region) {
                if ($region->is_base_level and $l->region->is_top_level) {
                    return $l->region->code;
                } else {
                    return '';
                }
            })
            ->editColumn('updated_at', function ($l) {
                return ($l->updated_at == null) ? null : $l->updated_at->format('d.m.Y H:i');
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

        Log::info('preparing select2 club list for a league', ['league'=>$league->id, 'count' => count($clubs)] );
        $response = array();

        foreach ($clubs as $c) {
            if ($leagueclubs->contains($c->id)) {
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

        Log::info('preparing select2 league list', ['count' => count($leagues)] );
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
        $response = array();

        Log::info('preparing select2 free league places list', ['count' => count($freechars)] );
        foreach ($freechars as $key => $value) {
            $response[] = array(
                "id" => $key + 1,
                "text" => ($key + 1) . ' - ' . $value
            );
        }

        return Response::json($response);
    }


    /**
     * Display a dashboard
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard(Request $request, $language, League $league)
    {

        if ( !Bouncer::canAny(['create-leagues', 'update-leagues'])) {
            Log::warning('[ACCESS DENIED]',['url'=> $request->path(), 'ip'=> $request->ip() ]);
            abort(403);
        }
        $data['league'] = $league;

        $data['members'] = Member::whereIn('id', League::find($league->id)->members()->pluck('member_id'))->with('memberships')->get();

        $data['games'] = $data['league']->games()->get();
        //Log::debug(print_r($assigned_team,true));
        $directory =  $league->region->league_folder;
        $reports = collect(Storage::disk('exports')->files($directory))->filter(function ($value) use ($league) {
            return Str::contains($value, $league->shortname);
        });

        //Log::debug(print_r($reports,true));
        $data['files'] = $reports;

        Log::info('showing league dashboard',['league-id'=>$league->id]);
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

        Log::info('showing league briefing',['league-id'=>$league->id]);
        return view('league/league_briefing', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($language, Region $region)
    {
        Log::info('create new league');
        return view(
            'league/league_new',
            [
                'region' => $region,
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
    public function store(Request $request, Region $region)
    {
        $data = $request->validate([
            'shortname' => array(
                'required',
                'string',
                'unique:leagues',
                'max:10'
            ),
            'league_size_id' => 'sometimes|required|exists:league_sizes,id',
            'schedule_id' => 'sometimes|required|exists:schedules,id',
            'name' => 'required|max:255',
            'age_type' => ['required', new EnumValue(LeagueAgeType::class, false)],
            'gender_type' => ['required', new EnumValue(LeagueGenderType::class, false)],

        ]);
        Log::info('league form data validated OK.');

        $above_region = $request->input('above_region');
        if (isset($above_region) and ($above_region === 'on')) {
            $data['above_region'] = True;
        } else {
            $data['above_region'] = False;
        }


        $league = new League($data);
        $region->leagues()->save($league);
        Log::notice('new league created.', ['league-id'=>$league->id]);

        return redirect()->route('league.index', ['language' => app()->getLocale(), 'region' => $region]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\League  $league
     * @return \Illuminate\Http\Response
     */
    public function show(League $league)
    {
        return back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\League  $league
     * @return \Illuminate\Http\Response
     */
    public function edit($language, League $league)
    {
        Log::debug('editing league.', ['league-id' => $league->id]);
        $member = $league->memberships()->with('member')->first();
        if (isset($member)) {
            $rmember = $member->member;
        } else {
            $rmember = null;
        }
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
        $data = $request->validate([
            'shortname' => array(
                'required',
                'string',
                Rule::unique('leagues')->ignore($league->id),
                'max:10'
            ),
            'schedule_id' => 'sometimes|required|exists:schedules,id',
            'league_size_id' => 'sometimes|required|exists:league_sizes,id',
            'name' => 'required|max:255',
            'age_type' => ['required', new EnumValue(LeagueAgeType::class, false)],
            'gender_type' => ['required', new EnumValue(LeagueGenderType::class, false)]
        ]);
        Log::info('league form data validated OK');

        $above_region = $request->input('above_region');
        if (isset($above_region) and ($above_region == 'on')) {
            $data['above_region'] = true;
        } else {
            $data['above_region'] = false;
        }

        if ($request->input('schedule_id') == null) {
            $data['schedule_id'] = null;
        }
        if ($request->input('league_size_id') == null) {
            $data['league_size_id'] = null;
        }

        $result = $league->update($data);
        $league->refresh();
        Log::notice('league updated', ['league-id'=> $league->id]);
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
        Log::info('league teams reset',['league-id'=>$league->id]);

        $mships = $league->memberships()->get();
        foreach ($mships as $ms) {
            $ms->delete();
        }
        Log::info('league memberships deleted',['league-id'=>$league->id]);

        $region = $league->region;
        $league->delete();
        Log::notice('league deleted',['league-id'=>$league->id]);

        return redirect()->route('league.index', ['language' => app()->getLocale(), 'region' => $region]);
    }

    /**
     * display management dashboard
     *
     */
    public function index_mgmt(Request $request, $language, Region $region)
    {
        if (!Bouncer::canAny(['create-leagues', 'update-leagues'])) {
            Log::warning('[ACCESS DENIED]',['url'=> $request->path(), 'ip'=> $request->ip() ]);
            abort(403);
        }
        Log::info('showing league management list');
        return view('league.league_list_mgmt', ['language' => $language, 'region' => $region]);
    }
    /**
     * league datatables club assignments
     *
     */
    public function list_mgmt(Request $request, $language, Region $region)
    {
        if (!Bouncer::canAny(['create-leagues', 'update-leagues'])) {
            Log::warning('[ACCESS DENIED]',['url'=> $request->path(), 'ip'=> $request->ip() ]);
            abort(403);
        }

        if ($region->is_base_level) {
            Log::notice('getting leagues for base level region');
            $leagues = League::whereIn('region_id', [$region->id, $region->parentRegion->id])->get();
        } else {
            Log::notice('getting leagues for top level region');
            $leagues = $region->leagues;
        }


        //Log::debug(print_r($leagues,true));

        $leaguelist = datatables()::of($leagues);
        Log::info('preparing league list');
        app()->setLocale($language);

        return $leaguelist
            ->addIndexColumn()
            ->rawColumns([
                'shortname.display', 'nextaction', 'rollbackaction', 'clubs', 'teams',
                'state'
            ])
            ->editColumn('shortname', function ($l) {
                $link = '<a href="' . route('league.dashboard', ['language' => Auth::user()->locale, 'league' => $l->id]) . '" >' . $l->shortname . '</a>';
                return array('display' => $link, 'sort' => $l->shortname);
            })
            ->addColumn('alien_region', function ($l) use ($region) {
                if ($region->is_base_level and $l->region->is_top_level) {
                    return $l->region->code;
                } else {
                    return '';
                }
            })
            ->addColumn('nextaction', function ($data) use ($region) {
                $btn = '';
                if ($region->is($data->region)) {
                    if ($data->state->is(LeagueState::Assignment())) {
                        $btn = '<button type="button" class="btn btn-primary btn-sm" id="changeState" data-league="' . $data->id . '"
                        data-action="' . LeagueStateChange::CloseAssignment() . '"><i class="fas fa-lock"> </i> ' . __('league.action.close.assignment') . '</button>';
                        $btn .= '<button type="button" class="btn btn-secondary btn-sm" id="assignClub" data-league="' . $data->id . '"
                        data-toggle="collapse" data-target="#collapseAssignment"><i class="fas fa-lock"> </i> Assign Clubs</button>';
                    } elseif ($data->state->is(LeagueState::Registration())) {
                        $btn = '<button type="button" class="btn btn-primary btn-sm" id="changeState" data-league="' . $data->id . '"
                        data-action="' . LeagueStateChange::CloseRegistration() . '"><i class="fas fa-lock"> </i> ' . __('league.action.close.registration') . '</button>';
                    } elseif ($data->state->is(LeagueState::Selection())) {
                        $btn = '<button type="button" class="btn btn-primary btn-sm" id="changeState" data-league="' . $data->id . '"
                        data-action="' . LeagueStateChange::CloseSelection() . '"><i class="fas fa-lock"> </i> ' . __('league.action.close.selection') . '</button>';
                    } elseif ($data->state->is(LeagueState::Freeze())) {
                        $btn = '<button type="button" class="btn btn-primary btn-sm" id="createGames" data-league="' . $data->id . '"><i class="fas fa-plus-circle"> </i> ' . __('league.action.close.freeze') . '
                        </button>';
                    } elseif ($data->state->is(LeagueState::Scheduling())) {
                        $btn = '<button type="button" class="btn btn-primary btn-sm" id="changeState" data-league="' . $data->id . '"
                        data-action="' . LeagueStateChange::CloseScheduling() . '"><i class="fas fa-lock"> </i> ' . __('league.action.close.scheduling') . '</button>';
                    } elseif ($data->state->is(LeagueState::Referees())) {
                        $btn = '<button type="button" class="btn btn-primary btn-sm" id="changeState" data-league="' . $data->id . '"
                        data-action="' . LeagueStateChange::CloseReferees() . '"><i class="fas fa-lock"> </i> ' . __('league.action.close.referees') . '</button>';
                    }
                }
                return $btn;
            })
            ->addColumn('rollbackaction', function ($data) use ($region) {
                $btn = '';
                if ($region->is($data->region)) {
                    if ($data->state->is(LeagueState::Registration())) {
                        $btn .= '<button type="button" class="btn btn-outline-danger btn-sm" id="changeState" data-league="' . $data->id . '"
                        data-action="' . LeagueStateChange::OpenAssignment() . '"><i class="fas fa-lock"> </i> ' . __('league.action.open.assignment') . '</button>';
                    } elseif ($data->state->is(LeagueState::Selection())) {
                        $btn .= '<button type="button" class="btn btn-outline-danger btn-sm" id="changeState" data-league="' . $data->id . '"
                        data-action="' . LeagueStateChange::OpenRegistration() . '"><i class="fas fa-lock"> </i> ' . __('league.action.open.registration') . '</button>';
                    } elseif ($data->state->is(LeagueState::Freeze())) {
                        $btn .= '<button type="button" class="btn btn-outline-danger btn-sm" id="changeState" data-league="' . $data->id . '"
                        data-action="' . LeagueStateChange::OpenSelection() . '"><i class="fas fa-lock"> </i> ' . __('league.action.open.selection') . '</button>';
                    } elseif ($data->state->is(LeagueState::Scheduling())) {
                        $btn .= '<button type="button" class="btn btn-outline-danger btn-sm" id="deleteGames" data-league="' . $data->id . '"><i class="fas fa-minus-circle"> </i> ' . __('league.action.open.freeze') . '
                        </button>';
                    } elseif ($data->state->is(LeagueState::Referees())) {
                        $btn .= '<button type="button" class="btn btn-outline-danger btn-sm" id="changeState" data-league="' . $data->id . '"
                        data-action="' . LeagueStateChange::OpenScheduling() . '"><i class="fas fa-lock"> </i> ' . __('league.action.open.scheduling') . '</button>';
                    } elseif ($data->state->is(LeagueState::Live())) {
                        $btn .= '<button type="button" class="btn btn-outline-danger btn-sm" id="changeState" data-league="' . $data->id . '"
                        data-action="' . LeagueStateChange::OpenReferees() . '"><i class="fas fa-lock"> </i> ' . __('league.action.open.referees') . '</button>';
                    }
                }
                return $btn;
            })
            ->addColumn('clubs', function ($data) {
                $btnlist = '';

                $ccnt = 1;
                foreach ($data->loadMissing('clubs')->clubs->pluck('shortname') as $k => $c) {
                    $btnlist .= '<button type="button" class="btn btn-info btn-sm">' . $c . '</button> ';
                    $ccnt += 1;
                };
                if ($data->state->is(LeagueState::Assignment())) {
                    for ($i = $ccnt; $i <= $data->size ?? 0; $i++) {
                        $btnlist .= '<button type="button" class="btn btn-danger btn-sm" ><i class="fas fa-question"></i></button> ';
                    }
                }

                return $btnlist;
            })
            ->addColumn('teams', function ($l) {
                $btnlist = '';
                $ccnt = 1;
                $c = $l->clubs->pluck('shortname');
                foreach ($l->teams()->with('club')->orderBy('league_no')->get()->pluck('league_no', 'club.shortname') as $lt => $lnr) {
                    if ($c->contains($lt)) {
                        if ($lnr == null) {
                            $clr = 'btn-warning';
                        } else {
                            $clr = 'btn-success';
                        }
                    } else {
                        $clr = 'btn-danger';
                    }
                    $btnlist .= '<button type="button" class="btn ' . $clr . ' btn-sm">' . $lt . ' <span class="badge badge-pill badge-light">' . $lnr . '</span></button> ';
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

    /**
     * league teams datatable
     *
     */
    public function team_dt(Request $request, $language, League $league)
    {
        $clubteam = collect();
        $c_keys = collect( range(1, $league->size));
        $t_keys = collect( range(1, $league->size));

        $clubs = $league->clubs->sortBy('pivot.league_no');
        foreach ($clubs as $c){
            $clubteam[ ] = array(
                'club_shortname' => $c->shortname,
                'club_league_no' => $c->pivot->league_no ?? null,
                'club_id' => $c->id,
                'team_id' => null,
                'team_name' => null,
                'team_league_no' => null,
                'team_league_char' => null,
                'team_no' => null,
                'region_code' => $c->region->code );
            if ($c->pivot->league_no != null ) { $c_keys->pull($c->pivot->league_no-1); };
        }
        $teams = $league->teams;

        $clubteam->transform( function ($item) use (&$teams, &$t_keys) {
            $k = $teams->search(function ($t) use ($item) {
                return (($t['club_id'] == $item['club_id']) and ($item['team_id'] == null));
            });
            if ($k !== false ){
                $item['team_id'] = $teams[$k]->id;
                $item['team_name'] = $teams[$k]->name;
                $item['team_league_no'] = $teams[$k]->league_no;
                $item['team_league_char'] = $teams[$k]->league_char;
                $item['team_no'] = $teams[$k]->team_no;

                if ($teams[$k]->league_no != null ) { $t_keys->pull($teams[$k]->league_no-1); };

                $teams->pull($k);
            }
            return $item;
        });

        foreach ($teams as $t){
            $clubteam[] = array(
                'club_shortname' => null,
                'club_league_no' => null,
                'club_id' => null,
                'team_id' => $t->id,
                'team_name' => $t->name,
                'team_league_no' => $t->league_no,
                'team_league_char' => $t->league_char,
                'team_no' => $t->team_no ,
                'region_code' => null );
            if ($t->league_no != null ) { $t_keys->pull($t->league_no-1); };
        }


        for ($i=count($clubteam); $i < ($league->size); $i++){
            $clubteam[] = array(
                'club_shortname' => null,
                'club_league_no' => null,
                'club_id' => null,
                'team_id' => null,
                'team_name' => null,
                'team_league_no' => null,
                'team_league_char' => null,
                'team_no' => null ,
                'region_code' => null );
        }

        $available_no = $t_keys->collect();

        $regions = collect();
        if ($league->region->is_top_level){
            $regions = $league->region->childRegions->pluck('code','id');
        } else {
            $regions = $league->region()->pluck('code','id');
        }


        $teamlist = datatables()::of($clubteam);
        Log::info('preparing team list');
        return $teamlist
            ->addIndexColumn()
            ->rawColumns([
                'club_shortname.display','team_name', 'team_league_no.display'
            ])
            ->editColumn('club_shortname', function ($ct) use($league, &$c_keys, $regions) {
                if ( (Auth::user()->can('update-leagues')) and ($league->state->in([ LeagueState::Assignment, LeagueState::Selection, LeagueState::Registration  ])) ){
                    if ($ct['club_shortname'] != null){
                        $btn = '<button id="deassignClub" data-id="'.$ct['club_id'].'" type="button" class="btn btn-success btn-sm">';
                        $btn .= $ct['club_shortname'];
                        $btn .= ( $league->region->is_top_level) ? ' ('.$ct['region_code'].')' : '';
                    } else {
                        if ($regions->count() > 1 ){
                            $btn = __('club.select.byregion');
                            foreach ($regions as $ri => $rc){
                                $btn .= '<button id="assignClub" data-region-id="'.$ri.'" data-region-code="'.$rc.'" type="button" class="btn btn-outline-info btn-sm">';
                                $btn .= $rc.'</button>';
                            }

                        } else {
                            $btn = '<button id="assignClub" data-region-id="'.$regions->keys()->first().'" data-region-code="'.$regions->first().'" type="button" class="btn btn-outline-info btn-sm">';
                            $btn .= __('club.action.select').'</button>';
                        }
                    }
                } else {
                    $btn = $ct['club_shortname'] ?? '';
                }
                if ($ct['club_league_no'] != null){
                    $sortkey = $ct['club_league_no'];
                } else {
                    $sortkey = $c_keys->shift();
                }

                return array('display' => $btn, 'sort' => $sortkey);
            })
            ->editColumn('team_name', function ($ct) use($league) {
                if ( (Auth::user()->can('update-leagues')) and
                     ($league->state->in([ LeagueState::Selection, LeagueState::Registration, LeagueState::Scheduling, LeagueState::Freeze  ]))){
                    if ($ct['team_name'] != null){
                        $btn = '<button type="button" class="btn btn-secondary btn-sm" id="unregisterTeam"';
                        $btn .= 'data-team-id="'.$ct['team_id'].'">'.$ct['team_name'].'</button>';
                    } else {
                        if ($ct['club_id'] != null ){
                            $unregistered_teams = Club::find($ct['club_id'])->teams->whereNull('league_id');
                            if ( $unregistered_teams->count() > 0){
                                $btn = '<div class="btn-group btn-group-sm"><button type="button" class="btn btn-sm btn-secondary dropdpwn-toggle" data-toggle="dropdown">'.__('team.action.select').'</button>';
                                $btn .= '<div class="dropdown-menu">';
                                foreach ($unregistered_teams as $urt){
                                    $btn .= '<a class="dropdown-item" href="javascript:registerTeam('.$urt->id.') ">'.$urt->name.'</a>';
                                }
                                $btn .='</div></div>';
                            } else {
                                $btn = __('team.noteam.avail', ['club'=>$ct['club_shortname']]);
                            }
                        } else {
                            $btn = '<button  type="button" class="btn btn-outline-info btn-sm" id="injectTeam"';
                            $btn .= '>'.__('league.action.register').'</button>';
                        };
                    };
                } else {
                    $btn = $ct['team_name'] ?? '';
                }
                return $btn;
            })
            ->editColumn('team_league_no', function ($ct) use($league, &$t_keys, $available_no) {
                if ( (Auth::user()->can('update-leagues')) and
                     ($league->state->in([ LeagueState::Selection, LeagueState::Scheduling, LeagueState::Freeze ]))){
                    if ($ct['team_league_no'] != null){
                        $btn = '<button type="button" class="btn btn-danger btn-sm" id="releaseChar"';
                        $btn .= 'data-team-id="'.$ct['team_id'].'" data-league-no="'.$ct['team_league_no'].'" ';
                        $btn .= '>'.$ct['team_league_no'].'</button>';
                    } else {
                        $btn = '';
                        if ($ct['team_id']!= null){
                            $btn = __('league.sb_freechar').' ';
                            foreach ($available_no as $an){
                                $btn .= '<button  type="button" class="btn btn-outline-info btn-sm" id="pickChar"';
                                $btn .= 'data-team-id="'.$ct['team_id'].'" data-league-no="'.$an.'" ';
                                $btn .= '>'.$an.'</button>';
                            }
                        }
                    }
                } else {
                    $btn = $ct['team_league_no'] ?? '';
                }

                if ($ct['team_league_no'] != null){
                    $sortkey = $ct['team_league_no'];
                } else {
                    $sortkey = $t_keys->shift();
                }

                return array('display' => $btn, 'sort' => $sortkey);
            })
            ->make(true);


    }
}
