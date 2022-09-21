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
use App\Traits\LeagueTeamManager;
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
use App\View\Components\LeagueContent;

class LeagueController extends Controller
{
    use LeagueTeamManager;
    /**
     * Display a listing of the resource.
     *
     * @param string $language
     * @param \App\Models\Region $region
     * @return \Illuminate\View\View
     *
     */
    public function index($language, Region $region)
    {
        Log::info('showing league list');
        return view('league/league_list', ['region' => $region]);
    }

    /**
     * Display a listing of the resource .
     *
     * @param string $language
     * @param \App\Models\Region $region
     * @return \Illuminate\Http\JsonResponse
     */
    public function list($language, Region $region)
    {

        app()->setLocale($language);

        if ($region->is_base_level) {
            Log::notice('getting leagues for top level region');
            $leagues = League::whereIn('region_id', [$region->id, $region->parentRegion->id])->with(['league_size','schedule.league_size','clubs.region','teams.club.region'])
                ->withCount([
                    'clubs', 'teams', 'registered_teams', 'selected_teams', 'games',
                    'games_notime', 'games_noshow'
                ])
                ->orderBy('shortname', 'ASC')
                ->get();
        } else {
            Log::notice('getting leagues for base level region');
            $leagues = League::where('region_id', $region->id)->with(['league_size', 'schedule.league_size','clubs.region','teams.club.region'])
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
                if ( ((Bouncer::can('access', $l)) or (Bouncer::is(Auth::user())->a('regionadmin'))) and Bouncer::can('access',$l->region)) {
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
                if ($l->schedule != null ) {
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
                // $content = new LeagueStatus($l);
                $content = new LeagueContent($l);
                return $content->render()->with($content->data());
            })
            ->make(true);
    }


    /**
     * Display a listing of the resource for selectboxes. clubs for league
     *
     * @param \App\Models\League $league
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function sb_club(League $league)
    {
        //Log::debug(print_r($club,true));

        $clubs = League::find($league->id)->region->clubs()->active()->orderBy('shortname', 'ASC')->get();
        $leagueclubs = $league->clubs()->pluck('id');

        Log::info('preparing select2 club list for a league', ['league' => $league->id, 'count' => count($clubs)]);
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
     * @param \App\Models\Region $region
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function sb_region(Region $region)
    {

        $leagues = $region->leagues()->orderBy('shortname', 'ASC')->get();

        Log::info('preparing select2 league list', ['count' => count($leagues)]);
        $response = array();

        foreach ($leagues as $league) {
            $response[] = array(
                "id" => $league->id,
                "text" => $league->shortname
            );
        }
        return Response::json($response);
    }

    /**
     * select2 list for all free cahractersd of a league
     *
     * @param \App\Models\League $league
     * @return \Illuminate\Http\JsonResponse
     *
     */
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

        Log::info('preparing select2 free league places list', ['count' => count($freechars)]);
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
     * @param Request $request
     * @param string $language
     * @param \App\Models\League $league
     * @return \Illuminate\View\View
     *
     */
    public function dashboard(Request $request, $language, League $league)
    {

        if ( Bouncer::cannot( 'access', $league)) {
            Log::warning('[ACCESS DENIED]', ['url' => $request->path(), 'ip' => $request->ip()]);
            abort(403);
        }
        $data['league'] = $league;
        $data['members'] = $data['league']->members->unique();

        $data['games'] = $data['league']->games()->get();
        //Log::debug(print_r($assigned_team,true));
        $directory =  $league->region->league_folder;
        $reports = collect(Storage::files($directory))->filter(function ($value) use ($league) {
            return Str::contains($value, $league->shortname);
        });

        //Log::debug(print_r($reports,true));
        $data['files'] = $reports;
        $data['scope'] = 'league';

        Log::info('showing league dashboard', ['league-id' => $league->id]);
        return view('league/league_dashboard', $data);
    }

    /**
     * Display a brief overview
     *
     * @param string $language
     * @param \App\Models\League $league
     * @return \Illuminate\View\View
     *
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
        $data['scope'] = 'league';

        Log::info('showing league briefing', ['league-id' => $league->id]);
        return view('league/league_briefing', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param string $language
     * @param \App\Models\Region $region
     * @return \Illuminate\View\View
     *
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
     * @param \App\Models\Region $region
     * @return \Illuminate\Http\RedirectResponse
     *
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

        $league = new League($data);
        $region->leagues()->save($league);
        Log::notice('new league created.', ['league-id' => $league->id]);

        return redirect()->route('league.index', ['language' => app()->getLocale(), 'region' => $region]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param string $language
     * @param  \App\Models\League  $league
     * @return \Illuminate\View\View
     *
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
     * @return \Illuminate\Http\RedirectResponse
     *
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

        if ( !$request->has('schedule_id')) {
            $data['schedule_id'] = null;
        }
        if ( !$request->has('league_size_id')) {
            $data['league_size_id'] = null;
        }

        $result = $league->update($data);
        $league->refresh();
        Log::notice('league updated', ['league-id' => $league->id]);
        return redirect()->route('league.dashboard', ['language' => app()->getLocale(), 'league' => $league]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\League  $league
     * @return \Illuminate\Http\RedirectResponse
     *
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
        Log::info('league teams reset', ['league-id' => $league->id]);

        $mships = $league->memberships()->get();
        foreach ($mships as $ms) {
            $ms->delete();
        }
        Log::info('league memberships deleted', ['league-id' => $league->id]);

        $region = $league->region;
        $league->delete();
        Log::notice('league deleted', ['league-id' => $league->id]);

        return redirect()->route('league.index', ['language' => app()->getLocale(), 'region' => $region]);
    }

    /**
     * Display a view to manage all leagues of a region
     *
     * @param Request $request
     * @param string $language
     * @param \App\Models\Region $region
     * @return \Illuminate\View\View
     *
     */
    public function index_mgmt(Request $request, $language, Region $region)
    {
        if (Bouncer::is(Auth::user())->notA('superadmin','regionadmin','leagueadmin')) {
            Log::warning('[ACCESS DENIED]', ['url' => $request->path(), 'ip' => $request->ip()]);
            abort(403);
        }
        Log::info('showing league management list');

        $states = $region->leagues()->pluck('state')->unique();

        return view('league.league_list_mgmt', ['language' => $language, 'region' => $region, 'states'=> $states ]);
    }

    /**
     * datatables.net with a league of a region (for maagement)
     *
     * @param Request $request
     * @param string $language
     * @param \App\Models\Region $region
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function list_mgmt(Request $request, $language, Region $region)
    {

/*         $btn = '<div class="btn-group"><button type="button" class="btn btn-secondary dropdpwn-toggle" data-toggle="dropdown">'.__('league.action.select').' ('.__('previous').': '. $ct->league_prev .')</button>';
        $btn .= '<div class="dropdown-menu">';
        foreach ($clubleagues as $cl){
            $btn .= '<a class="dropdown-item" href="javascript:registerTeam('.$cl->id.','.$ct->id.') ">'.$cl->shortname.'</a>';
        }
        $btn .='</div></div>'; */

        if (!Bouncer::canAny(['create-leagues', 'update-leagues'])) {
            Log::warning('[ACCESS DENIED]', ['url' => $request->path(), 'ip' => $request->ip()]);
            abort(403);
        }

        if ($region->is_base_level) {
            $leagues = League::whereIn('region_id', [$region->id, $region->parentRegion->id])->with('clubs', 'teams.club','schedule','schedule.league_size')->get();
            Log::notice('getting leagues for base level region',['count'=>count($leagues)]);
        } else {
            $leagues = League::whereIn('region_id', [$region->id])->with('clubs', 'teams.club','schedule','schedule.league_size')->get();
            Log::notice('getting leagues for top level region',['count'=>count($leagues)]);
        }

        foreach ($leagues as $l) {
            list($clubteam, $c_keys, $t_keys) = $this->get_registrations($l);
            $available_no = $t_keys->collect();

            for ($i = 1; $i < 17; $i++) {
                if ($i <= $l->size) {
                    $x = 0;
                    $ckey = collect($clubteam)->search( function ($item, $key) use($i) {
                        return ($item['team_league_no'] == $i);
                    });
                    if ($ckey !== false){
                        $c = $clubteam->pull($ckey);
                    } else {
                        $ckey = $clubteam->search( function ($item) {
                            return $item['team_league_no'] == null;
                        });
                        $c = $clubteam->pull($ckey);
                    }
                    list($btn_status, $btn_color, $btn_text, $btn_function, $subbtn_color) = $this->get_button_settings(
                        $l,
                        Auth::user(),
                        $c['club_id'],
                        $c['team_id'],
                        $c['club_league_no'],
                        $c['team_league_no'],
                        $c['club_shortname'],
                        $c['team_name']
                    );
                    $btn_function = Str::of($btn_function)->explode('#');
                    $subbtn_color = Str::of($subbtn_color)->explode('#');
                    if ( $btn_function->count() == 2 ){
                        $btn = '<div class="btn-group" role="group">';
                        $btn .= '<button type="button" class="btn btn-sm dropdpwn-toggle '.$btn_color.'" data-toggle="dropdown" '.$btn_status.'>'.$btn_text.'</button>';
                        $btn .= '<div class="dropdown-menu">';
                        foreach ($btn_function as $k=>$bf){
                            if ( $bf == 'pickChar'){
                                $btn .= __('league.action.'.$bf).': ';
                                foreach( $available_no as $an ){
                                    $btn .= '<button id="'.$bf.'" type="button" class="btn btn-sm '.$subbtn_color[$k].'" '.
                                    ' data-club-id="' . $c['club_id'] . '"'.
                                    ' data-club-shortname="' . $c['club_shortname'] . '"'.
                                    ' data-team-name="' . $c['team_name'] . '"'.
                                    ' data-team-id="' . $c['team_id'] . '"'.
                                    ' data-region-id="' . $l->region->id . '"'.
                                    ' data-league-no="' . $an . '"'.
                                    ' data-league-id="' . $l->id . '">'
                                    . $an . '</button>';
                                }
                            } else {
                                $btn .= '<button id="'.$bf.'" type="button" class="btn btn-sm '.$subbtn_color[$k].'" '.
                                ' data-club-id="' . $c['club_id'] . '"'.
                                ' data-club-shortname="' . $c['club_shortname'] . '"'.
                                ' data-team-id="' . $c['team_id'] . '"'.
                                ' data-team-name="' . $c['team_name'] . '"'.
                                ' data-region-id="' . $l->region->id . '"'.
                                ' data-league-no="' .$c['team_league_no'] . '"'.
                                ' data-league-id="' . $l->id . '">'
                                . __('league.action.'.$bf) . '</button>';
                            }
                        }
                        $btn .='</div></div>';
                        $l['t' . $i] = $btn;
                    } else {
                        $l['t' . $i] = '<button id="'.$btn_function->pop().'" type="button" class="btn btn-sm '.$btn_color.'" '.$btn_status .
                                        ' data-club-id="' . $c['club_id'] . '"'.
                                        ' data-club-shortname="' . $c['club_shortname'] . '"'.
                                        ' data-team-id="' . $c['team_id'] . '"'.
                                        ' data-team-name="' . $c['team_name'] . '"'.
                                        ' data-region-id="' . $l->region->id . '"'.
                                        ' data-league-no="' . $c['team_league_no'] . '"'.
                                        ' data-league-id="' . $l->id . '">'
                                        . $btn_text . '</button>';
                    }

                } else {
                    $l['t' . $i] = 'X';
                }
            }
        }

        //Log::debug(print_r($leagues,true));

        $leaguelist = datatables()::of($leagues);
        Log::info('preparing league list');
        app()->setLocale($language);

        return $leaguelist
            ->addIndexColumn()
            ->rawColumns([
                'shortname.display', 'nextaction', 'rollbackaction', 'clubs', 'teams',
                'state',
                't1', 't2', 't3', 't4', 't5', 't6', 't7', 't8', 't9', 't10', 't11', 't12', 't13', 't14', 't15', 't16'
            ])
            ->addColumn('schedulename', function ($l) {
                if ($l->schedule == null){
                    return __('Undefined');
                } else {
                    if ($l->schedule->custom_events){
                        return __('Custom');
                    } else {
                        return $l->schedule->name;
                    }
                }
            })
            ->editColumn('shortname', function ($l) {
                if ($l->load('schedule')->is_custom){
                    $link = '📅 ';
                } elseif ($l->load('schedule')->is_not_ready){
                    $link = '🔴 ';
                } else {
                    $link = '';
                }

                if ( ((Bouncer::can('access',$l)) or (Bouncer::is(Auth::user())->a('regionadmin'))) and
                     (Bouncer::can('access',$l->region)) ){
                    $link .= '<a href="' . route('league.dashboard', ['language' => Auth::user()->locale, 'league' => $l->id]) . '" >' . $l->shortname . '</a>';
                } else {
                    $link .= '<a href="' . route('league.briefing', ['language' => Auth::user()->locale, 'league' => $l->id]) . '" >' . $l->shortname . '</a>';
                }
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
                if ( (Auth::user()->isAn('regionadmin','superadmin')) or (Bouncer::can('access', $data))){
                    if ($region->is($data->region)) {
                        if ($data->state->is(LeagueState::Setup())) {
                            $btn = '<button type="button" class="btn btn-primary btn-sm" id="changeState" data-league="' . $data->id . '"
                            data-action="' . LeagueStateChange::StartLeague() . '"><i class="fas fa-lock"> </i> ' . __('league.action.open.registration') . '</button>';
                        } elseif ($data->state->is(LeagueState::Registration())) {
                            if ($data->is_custom){
                                $btn = '<button type="button" class="btn btn-primary btn-sm" id="changeState" data-league="' . $data->id . '"
                                data-action="' . LeagueStateChange::FreezeLeague() . '"><i class="fas fa-lock"> </i> ' . __('league.action.close.freeze') . '</button>';
                            } else {
                                $btn = '<button type="button" class="btn btn-primary btn-sm" id="changeState" data-league="' . $data->id . '"
                                data-action="' . LeagueStateChange::OpenSelection() . '"><i class="fas fa-lock"> </i> ' . __('league.action.close.selection') . '</button>';
                            }
                        } elseif ($data->state->is(LeagueState::Selection())) {
                            $btn = '<button type="button" class="btn btn-primary btn-sm" id="changeState" data-league="' . $data->id . '"
                            data-action="' . LeagueStateChange::FreezeLeague() . '"><i class="fas fa-lock"> </i> ' . __('league.action.close.freeze') . '</button>';
                        } elseif ($data->state->is(LeagueState::Freeze())) {
                            $btn = '<button type="button" class="btn btn-primary btn-sm" id="changeState" data-league="' . $data->id . '"
                            data-action="' . LeagueStateChange::OpenScheduling() . '"><i class="fas fa-lock"> </i> ' . __('league.action.close.scheduling') . '</button>';
                        } elseif ($data->state->is(LeagueState::Scheduling())) {
                            $btn = '<button type="button" class="btn btn-primary btn-sm" id="changeState" data-league="' . $data->id . '"
                            data-action="' . LeagueStateChange::OpenReferees() . '"><i class="fas fa-lock"> </i> ' . __('league.action.close.referees') . '</button>';
                        } elseif ($data->state->is(LeagueState::Referees())) {
                            $btn = '<button type="button" class="btn btn-primary btn-sm" id="changeState" data-league="' . $data->id . '"
                            data-action="' . LeagueStateChange::GoLiveLeague() . '"><i class="fas fa-lock"> </i> ' . __('league.action.close.golive') . '</button>';
                        }

                    }
                }
                return $btn;
            })
            ->addColumn('rollbackaction', function ($data) use ($region) {
                $btn = '';
                if ( (Auth::user()->isAn('regionadmin','superadmin')) or (Bouncer::can('access', $data))){
                    if ($region->is($data->region)) {
                        if ($data->state->is(LeagueState::Registration())) {
                            $btn .= '<button type="button" class="btn btn-outline-danger btn-sm" id="changeState" data-league="' . $data->id . '"
                            data-action="' . LeagueStateChange::CloseLeague() . '"><i class="fas fa-lock"> </i> ' . __('league.action.close.setup') . '</button>';
                        } elseif ($data->state->is(LeagueState::Selection())) {
                            $btn .= '<button type="button" class="btn btn-outline-danger btn-sm" id="changeState" data-league="' . $data->id . '"
                            data-action="' . LeagueStateChange::ReOpenRegistration() . '"><i class="fas fa-lock"> </i> ' . __('league.action.open.registration') . '</button>';
                        } elseif ($data->state->is(LeagueState::Freeze())) {
                            if ($data->is_custom){
                                $btn .= '<button type="button" class="btn btn-outline-danger btn-sm" id="changeState" data-league="' . $data->id . '"
                                data-action="' . LeagueStateChange::ReOpenRegistration() . '"><i class="fas fa-lock"> </i> ' . __('league.action.open.registration') . '</button>';
                            } else {
                                $btn .= '<button type="button" class="btn btn-outline-danger btn-sm" id="changeState" data-league="' . $data->id . '"
                                data-action="' . LeagueStateChange::ReOpenSelection() . '"><i class="fas fa-lock"> </i> ' . __('league.action.open.selection') . '</button>';
                            };
                        } elseif ($data->state->is(LeagueState::Scheduling())) {
                            $btn .= '<button type="button" class="btn btn-outline-danger btn-sm" id="changeState" data-league="' . $data->id . '"
                            data-action="' . LeagueStateChange::ReFreezeLeague() . '"><i class="fas fa-lock"> </i> ' . __('league.action.open.freeze') . '
                            </button>';
                        } elseif ($data->state->is(LeagueState::Referees())) {
                            $btn .= '<button type="button" class="btn btn-outline-danger btn-sm" id="changeState" data-league="' . $data->id . '"
                            data-action="' . LeagueStateChange::ReOpenScheduling() . '"><i class="fas fa-lock"> </i> ' . __('league.action.open.scheduling') . '</button>';
                        } elseif ($data->state->is(LeagueState::Live())) {
                            $btn .= '<button type="button" class="btn btn-outline-danger btn-sm" id="changeState" data-league="' . $data->id . '"
                            data-action="' . LeagueStateChange::ReOpenReferees() . '"><i class="fas fa-lock"> </i> ' . __('league.action.open.referees') . '</button>';
                        }
                    }
                }
                return $btn;
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
     * databtales.net list with all teamsa fo a league
     *
     * @param Request $request
     * @param string $language
     * @param \App\Models\League $league
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function team_dt(Request $request, $language, League $league)
    {
        list($clubteam, $c_keys, $t_keys) = $this->get_registrations($league);
        $available_no = $t_keys->collect();

        $regions = collect();
        if ($league->region->is_top_level) {
            $regions = $league->region->childRegions->pluck('code', 'id');
        } else {
            $regions = $league->region()->pluck('code', 'id');
        }


        $teamlist = datatables()::of($clubteam);
        Log::info('preparing team list');
        return $teamlist
            ->addIndexColumn()
            ->rawColumns([
                'club_shortname.display', 'team_name', 'team_league_no.display'
            ])
            ->editColumn('club_shortname', function ($ct) use ($league, &$c_keys, $regions) {
                if ((Auth::user()->can('update-leagues')) and ($league->state->in([ LeagueState::Selection, LeagueState::Registration, LeagueState::Scheduling, LeagueState::Freeze]))
                    and (Auth::user()->can('access',$league->region))) {
                    if ($ct['club_shortname'] != null) {
                        $btn = '<button id="deassignClub" data-id="' . $ct['club_id'] . '" type="button" class="btn btn-success btn-sm">';
                        $btn .= $ct['club_shortname'];
                        $btn .= ($league->region->is_top_level) ? ' (' . $ct['region_code'] . ')' : '';
                    } else {
                        if ($regions->count() > 1) {
                            $btn = __('club.select.byregion');
                            foreach ($regions as $ri => $rc) {
                                $btn .= '<button id="assignClub" data-region-id="' . $ri . '" data-region-code="' . $rc . '" type="button" class="btn btn-outline-info btn-sm">';
                                $btn .= $rc . '</button>';
                            }
                        } else {
                            $btn = '<button id="assignClub" data-region-id="' . $regions->keys()->first() . '" data-region-code="' . $regions->first() . '" type="button" class="btn btn-outline-info btn-sm">';
                            $btn .= __('club.action.select') . '</button>';
                        }
                    }
                } else {
                    $btn = $ct['club_shortname'] ?? '';
                }
                if ($ct['club_league_no'] != null) {
                    $sortkey = $ct['club_league_no'];
                } else {
                    $sortkey = $c_keys->shift();
                }

                return array('display' => $btn, 'sort' => $sortkey);
            })
            ->editColumn('team_name', function ($ct) use ($league) {
                if ((Auth::user()->can('update-leagues')) and
                    ($league->state->in([LeagueState::Selection, LeagueState::Registration, LeagueState::Scheduling, LeagueState::Freeze])) and
                    (Auth::user()->can('access', $league->region))
                ) {
                    if ($ct['team_name'] != null) {
                        $btn = '<button type="button" class="btn btn-secondary btn-sm" id="unregisterTeam"';
                        $btn .= 'data-team-id="' . $ct['team_id'] . '">' . $ct['team_name'] . '</button>';
                    } else {
                        if ($ct['club_id'] != null) {
                            $unregistered_teams = Club::find($ct['club_id'])->teams->whereNull('league_id')->load('club','members');
                            if ($unregistered_teams->count() > 0) {
                                $btn = '<div class="btn-group btn-group-sm"><button type="button" class="btn btn-sm btn-secondary dropdpwn-toggle" data-toggle="dropdown">' . __('team.action.select') . '</button>';
                                $btn .= '<div class="dropdown-menu">';
                                foreach ($unregistered_teams as $urt) {
                                    $btn .= '<a class="dropdown-item" href="javascript:registerTeam(' . $urt->id . ') ">' . $urt->namedesc.'</a>';
                                }
                                $btn .= '</div></div>';
                            } else {
                                $btn = __('team.noteam.avail', ['club' => $ct['club_shortname']]);
                            }
                        } else {
                            $btn = '<button  type="button" class="btn btn-outline-info btn-sm" id="injectTeam"';
                            $btn .= '>' . __('league.action.register') . '</button>';
                        };
                    };
                } else {
                    $btn = $ct['team_name'] ?? '';
                }
                return $btn;
            })
            ->editColumn('team_league_no', function ($ct) use ($league, &$t_keys, $available_no) {
                if ((Auth::user()->can('update-leagues')) and
                    ($league->state->in([LeagueState::Registration, LeagueState::Selection, LeagueState::Scheduling, LeagueState::Freeze])) and
                    (Auth::user()->can('access', $league->region))
                ) {
                    if ($ct['team_league_no'] != null) {
                        $btn = '<button type="button" class="btn btn-danger btn-sm" id="releaseChar"';
                        $btn .= 'data-team-id="' . $ct['team_id'] . '" data-league-no="' . $ct['team_league_no'] . '" ';
                        $btn .= '>' . $ct['team_league_no'] . '</button>';
                    } else {
                        $btn = '';
                        if ($ct['team_id'] != null) {
                            $btn = __('league.sb_freechar') . ' ';
                            foreach ($available_no as $an) {
                                $btn .= '<button  type="button" class="btn btn-outline-info btn-sm" id="pickChar"';
                                $btn .= 'data-team-id="' . $ct['team_id'] . '" data-league-no="' . $an . '" ';
                                $btn .= '>' . $an . '</button>';
                            }
                        }
                    }
                } else {
                    $btn = $ct['team_league_no'] ?? '';
                }

                if ($ct['team_league_no'] != null) {
                    $sortkey = $ct['team_league_no'];
                } else {
                    $sortkey = $t_keys->shift();
                }

                return array('display' => $btn, 'sort' => $sortkey);
            })
            ->make(true);
    }
}
