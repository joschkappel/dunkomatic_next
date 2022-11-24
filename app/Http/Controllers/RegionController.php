<?php

namespace App\Http\Controllers;

use App\Enums\JobFrequencyType;
use App\Enums\LeagueAgeType;
use App\Enums\LeagueGenderType;
use App\Enums\LeagueState;
use App\Enums\ReportFileType;
use App\Enums\Role;
use App\Models\Game;
use App\Models\League;
use App\Models\Member;
use App\Models\Region;
use BenSampo\Enum\Rules\EnumValue;
use Datatables;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Silber\Bouncer\BouncerFacade as Bouncer;

class RegionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        Log::info('showing region list');

        return view('region.region_list');
    }

    /**
     * Display a dashboard
     *
     * @param  Request  $request
     * @param  string  $language
     * @param  \App\Models\Region  $region
     * @return \Illuminate\View\View
     */
    public function dashboard(Request $request, $language, Region $region)
    {
        if (! Bouncer::canAny(['create-regions', 'update-regions'])) {
            Log::warning('[ACCESS DENIED]', ['url' => $request->path(), 'ip' => $request->ip()]);
            abort(403);
        }
        $data['region'] = Region::withCount('clubs', 'gyms', 'teams', 'leagues', 'childRegions')->find($region->id);
        $data['members'] = $data['region']->members->unique();
        $data['member_count'] = $region->clubs()->with('members')->get()->pluck('members.*.id')->flatten()->concat(
            $region->leagues()->with('members')->get()->pluck('members.*.id')->flatten()
        )->concat(
            $region->members->pluck('id')->flatten()
        )->unique()->count();

        $data['games_count'] = Game::where('region_id_league', $region->id)->count();
        $data['games_noref_count'] = $region->clubs()->with('games_noreferee')->get()->pluck('games_noreferee.*.id')->flatten()->count();
        $data['scope'] = 'region';

        Log::info('showing region dashboard', ['region-id' => $region->id]);

        return view('region.region_dashboard', $data);
    }

    /**
     * Display a brief overview
     *
     * @param  string  $language
     * @param  \App\Models\Region  $region
     * @return \Illuminate\View\View
     */
    public function briefing($language, Region $region)
    {
        $data['region'] = $region;

        $data['memberships'] = $region->memberships()->with('member')->get();

        $data['clubs'] = $region->clubs()->with('members')->orderBy('shortname')->get();

        $data['leagues'] = $region->leagues()->with('members')->orderBy('shortname')->get();
        $data['scope'] = 'region';

        Log::info('showing region briefing', ['region-id' => $region->id]);

        return view('region/region_briefing', $data);
    }

    /**
     * Display view to create a new  region
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        Log::info('create new region');

        return view('region.region_new');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'region_id' => 'sometimes|exists:regions,id',
            'name' => 'required',
            'code' => 'required',
        ]);
        Log::info('region form data validated OK.');

        if (isset($data['region_id'])) {
            $data['hq'] = Region::findOrFail($data['region_id'])->code;
            unset($data['region_id']);
        }

        $region = Region::create($data);
        Log::notice('new region created.', ['region-id' => $region->id]);

        return redirect()->route('region.index', ['language' => app()->getLocale()]);
    }

    /**
     * datatables.net listing all regions
     *
     * @param  string  $language
     * @return \Illuminate\Http\JsonResponse
     */
    public function datatable($language)
    {
        $regions = Region::with('regionadmins')->withCount('clubs', 'leagues', 'teams', 'gyms')->get();

        Log::info('preparing region list');
        $regionlist = datatables()::of($regions);

        return $regionlist
            ->addIndexColumn()
            ->rawColumns(['regionadmin', 'code'])
            ->editColumn('code', function ($data) {
                if ((Bouncer::can('access', $data)) and (Bouncer::is(Auth::user())->a('regionadmin', 'superadmin'))) {
                    return '<a href="'.route('region.dashboard', ['language' => Auth::user()->locale, 'region' => $data->id]).'">'.$data->code.'</a>';
                } else {
                    return '<a href="'.route('region.briefing', ['language' => Auth::user()->locale, 'region' => $data->id]).'">'.$data->code.'</a>';
                }
            })
            ->editColumn('regionadmin', function ($r) {
                if ($r->regionadmins()->exists()) {
                    $admin = $r->regionadmins()->first()->firstname.' '.$r->regionadmins()->first()->lastname;
                } else {
                    $admin = '<a href="'.route('membership.region.create', ['language' => Auth::user()->locale, 'region' => $r->id]).'"><i class="fas fa-plus-circle"></i></a>';
                }

                return $admin;
            })
            ->make(true);
    }

    /**
     * select2 list with all regions
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function admin_sb()
    {
        $regions = Region::all();

        Log::info('preparing select2 region (with admins) list', ['count' => count($regions)]);
        $response = [];

        foreach ($regions as $region) {
            if ($region->regionadmins()->exists()) {
                $response[] = [
                    'id' => $region->id,
                    'text' => $region->name,
                ];
            }
        }

        return Response::json($response);
    }

    /**
     * select2 list with all top level regions
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function hq_sb()
    {
        $regions = Region::whereNull('hq')->get();

        Log::info('preparing select2 top region list', ['count' => count($regions)]);
        $response = [];

        foreach ($regions as $region) {
            $response[] = [
                'id' => $region->id,
                'text' => $region->name,
            ];
        }

        return Response::json($response);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string  $language
     * @param  \App\Models\Region  $region
     * @return \Illuminate\View\View
     */
    public function edit($language, Region $region)
    {
        Log::info('editing region.', ['region-id' => $region->id]);
        $filetypes = ReportFileType::getInstances();
        unset($filetypes[ReportFileType::ICS()->key]);
        unset($filetypes[ReportFileType::HTML()->key]);
        unset($filetypes[ReportFileType::XLSX()->key]);

        return view('region/region_edit', ['region' => $region->load('report_jobs'), 'frequencytype' => JobFrequencyType::getInstances(), 'filetype' => $filetypes]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Region  $region
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update_details(Request $request, Region $region)
    {
        $daterule = 'sometimes|nullable|date';

        $openselectionrule = $daterule;
        if ($request->has('open_selection_at')) {
            if ($request->has('close_selection_at')) {
                $openselectionrule .= '|before:close_selection_at';
            } elseif ($request->has('open_scheduling_at')) {
                $openselectionrule .= '|before:open_scheduling_at';
            } elseif ($request->has('close_scheduling_at')) {
                $openselectionrule .= '|before:close_scheduling_at';
            } elseif ($request->has('close_referees_at')) {
                $openselectionrule .= '|before:close_referees_at';
            }
        }

        $closeselectionrule = $daterule;
        if ($request->has('close_selection_at')) {
            if ($request->has('open_selection_at')) {
                $closeselectionrule .= '|after:open_selection_at';
            }

            if ($request->has('open_scheduling_at')) {
                $closeselectionrule .= '|before:open_scheduling_at';
            } elseif ($request->has('close_scheduling_at')) {
                $closeselectionrule .= '|before:close_scheduling_at';
            } elseif ($request->has('close_referees_at')) {
                $closeselectionrule .= '|before:close_referees_at';
            }
        }

        $openschedulingrule = $daterule;
        if ($request->has('open_scheduling_at')) {
            if ($request->has('close_selection_at')) {
                $openschedulingrule .= '|after:close_selection_at';
            } elseif ($request->has('open_selection_at')) {
                $openschedulingrule .= '|after:open_selection_at';
            }

            if ($request->has('close_scheduling_at')) {
                $openschedulingrule .= '|before:close_scheduling_at';
            } elseif ($request->has('close_referees_at')) {
                $openschedulingrule .= '|before:close_referees_at';
            }
        }

        $closeschedulingrule = $daterule;
        if ($request->has('close_scheduling_at')) {
            if ($request->has('open_scheduling_at')) {
                $closeschedulingrule .= '|after:open_scheduling_at';
            } elseif ($request->has('close_selection_at')) {
                $closeschedulingrule .= '|after:close_selection_at';
            } elseif ($request->has('open_selection_at')) {
                $closeschedulingrule .= '|after:open_selection_at';
            }

            if ($request->has('close_referees_at')) {
                $closeschedulingrule .= '|before:close_referees_at';
            }
        }

        $closerefereesrule = $daterule;
        if ($request->has('close_referees_at')) {
            if ($request->has('close_scheduling_at')) {
                $closerefereesrule .= '|after:close_scheduling_at';
            } elseif ($request->has('open_scheduling_at')) {
                $closerefereesrule .= '|after:open_scheduling_at';
            } elseif ($request->has('close_selection_at')) {
                $closerefereesrule .= '|after:close_selection_at';
            } elseif ($request->has('open_selection_at')) {
                $closerefereesrule .= '|after:open_selection_at';
            }
        }

        $data = $request->validate([
            'name' => 'required|max:40',
            'game_slot' => 'required|integer|in:60,75,90,105,120,135,150',
            'job_noleads' => ['required', new EnumValue(JobFrequencyType::class, false)],
            'job_email_valid' => ['required', new EnumValue(JobFrequencyType::class, false)],
            'fmt_club_reports' => 'nullable|array',
            'fmt_club_reports.*' => ['nullable', new EnumValue(ReportFileType::class, false)],
            'fmt_league_reports' => 'nullable|array|',
            'fmt_league_reports.*' => ['nullable', new EnumValue(ReportFileType::class, false)],
            'open_selection_at' => $openselectionrule,
            'close_selection_at' => $closeselectionrule,
            'open_scheduling_at' => $openschedulingrule,
            'close_scheduling_at' => $closeschedulingrule,
            'close_referees_at' => $closerefereesrule,
        ]);
        Log::info('region details form data validated OK.');

        $check = $region->update($data);
        $region->refresh();
        Log::notice('region updated.', ['region-id' => $region->id]);

        return redirect()->back();
    }



    /**
     * leagues by status for a region
     *
     * @param  \App\Models\Region  $region
     * @return \Illuminate\Http\JsonResponse
     */
    public function league_state_chart(Region $region)
    {
        Log::info('collecting league state chart data.', ['region-id' => $region->id]);

        $data = [];
        $data['labels'] = [];
        $datasets = [];
        // initialize datasets
        foreach (LeagueAgeType::getValues() as $at) {
            $datasets[$at]['stack'] = 'Stack 1';
            $datasets[$at]['label'] = LeagueAgeType::getDescription(LeagueAgeType::coerce($at));
            $datasets[$at]['data'] = [];
        }

        $rs = DB::table('leagues')
            ->where('region_id', $region->id)
            ->select('state', 'age_type', DB::raw('count(*) as total'))
            ->groupBy('state', 'age_type')
            ->orderBy('state')
            ->orderBy('age_type')->get();

        $data['labels'] = collect(LeagueState::getInstances())->pluck('description')->toArray();

        // initialize datasets
        foreach (LeagueState::getValues() as $ls) {
            foreach (LeagueAgeType::getValues() as $at) {
                $datasets[$at]['data'][] = $rs->where('state', $ls)->where('age_type', $at)->first()->total ?? 0;
            }
        }

        $data['datasets'] = $datasets;

        // Log::debug(print_r($data,true));

        return Response::json($data);
    }

    /**
     * leagues by age and gender for a region
     *
     * @param  \App\Models\Region  $region
     * @return \Illuminate\Http\JsonResponse
     */
    public function league_socio_chart(Region $region)
    {
        Log::info('collecting league social chart data.', ['region-id' => $region->id]);
        $data = [];
        $data['labels'] = [];
        $datasets = [];

        $rs = DB::table('leagues')->where('region_id', $region->id)->select('age_type', DB::raw('count(*) as total'))->groupBy('age_type')->get();
        // initialize dataset 0
        foreach (LeagueAgeType::getValues() as $at) {
            $data['labels'][] = LeagueAgeType::getDescription(LeagueAgeType::coerce($at));
            $datasets[0]['data'][] = $rs[$at]->total ?? 0;
        }
        $datasets[0]['backgroundColor'] = ['hsl(0, 100%, 60%)', 'hsl(0, 100%, 40%)', 'hsl(0, 100%, 20%)'];

        $rs = DB::table('leagues')->where('region_id', $region->id)->select('gender_type', DB::raw('count(*) as total'))->groupBy('gender_type')->get();
        // initialize dataset 1
        foreach (LeagueGenderType::getValues() as $gt) {
            $data['labels'][] = LeagueGenderType::getDescription(LeagueGenderType::coerce($gt));
            $datasets[1]['data'][] = $rs[$gt]->total ?? 0;
        }
        $datasets[1]['backgroundColor'] = ['hsl(100, 100%, 60%)', 'hsl(100, 100%, 40%)', 'hsl(100, 100%, 20%)'];

        $data['datasets'] = $datasets;

        // Log::debug(print_r($data,true));

        return Response::json($data);
    }

    /**
     * teams by club for a region
     *
     * @param  \App\Models\Region  $region
     * @return \Illuminate\Http\JsonResponse
     */
    public function club_team_chart(Region $region)
    {
        Log::info('collecting club teams chart data.', ['region-id' => $region->id]);
        $data = [];
        $data['labels'] = [];
        $datasets = [];
        // initialize datasets
        foreach (LeagueAgeType::getValues() as $at) {
            $datasets[$at]['stack'] = 'Stack 1';
            $datasets[$at]['label'] = LeagueAgeType::getDescription(LeagueAgeType::coerce($at));
            $datasets[$at]['data'] = [];
        }
        $notspecified = max(LeagueAgeType::getValues()) + 1;
        $datasets[$notspecified]['stack'] = 'Stack 1';
        $datasets[$notspecified]['label'] = __('reports.not_specified');
        $datasets[$notspecified]['data'] = [];

        /*       SELECT c.shortname, count(t.id)
      FROM clubs as c, teams as t
      WHERE c.region_id=2
      AND t.club_id = c.id
      GROUP BY c.shortname */
        $select = 'select c.shortname, l.age_type, count(l.age_type) as total ';
        $select .= ' FROM clubs as c, teams as t, leagues as l ';
        $select .= ' WHERE c.region_id = '.$region->id;
        $select .= ' AND t.club_id = c.id ';
        $select .= ' AND t.league_id = l.id ';
        $select .= ' GROUP BY c.shortname, l.age_type';
        $select .= ' ORDER BY c.shortname ASC, l.age_type ASC';

        $rs = collect(DB::select($select));

        // get lcubs sorted by team count
        $clubs = $region->clubs()->withCount('teams')->orderByDesc('teams_count')->orderBy('shortname')->pluck('teams_count', 'shortname');
        $data['labels'] = $clubs->keys()->toArray();

        foreach ($clubs as $c => $n) {
            $tot = $n;
            foreach (LeagueAgeType::getInstances() as $a) {
                $teams = $rs->where('shortname', $c)->where('age_type', $a->value)->first()->total ?? 0;
                $datasets[$a->value]['data'][] = $teams;
                $tot -= $teams;
            }
            $datasets[$notspecified]['data'][] = $tot ?? 0;
        }
        $data['datasets'] = $datasets;

        // Log::debug(print_r($data,true));

        return Response::json($data);
    }

    /**
     * members and roles by club for a region
     *
     * @param  \App\Models\Region  $region
     * @return \Illuminate\Http\JsonResponse
     */
    public function club_member_chart(Region $region)
    {
        Log::info('collecting club member chart data.', ['region-id' => $region->id]);
        $data = [];
        $data['labels'] = [];
        $datasets = [];
        // initialize datasets
        $roleList = Role::getValues();
        foreach ($roleList as $r) {
            $datasets[$r]['stack'] = 'Stack 1';
            $datasets[$r]['label'] = Role::getDescription(Role::coerce($r));
            $datasets[$r]['data'] = [];
        }

        /*       Membership::whereIn('member_id',Club::find(26)->members()->pluck('member_id'))
        ->get()
        ->groupBy('role_id')
        ->map(function ($item, $key) {
            return collect($item)->count();
          }); */
        $rs = $region->clubs()->with('memberships')->orderBy('shortname')->get();

        $clubs = $region->clubs()->withCount('memberships')->orderByDesc('memberships_count')->orderBy('shortname')->pluck('memberships_count', 'shortname');
        $data['labels'] = $clubs->keys()->toArray();

        foreach ($clubs as $c => $n) {
            foreach ($roleList as $r) {
                $mships = $rs->where('shortname', $c)->first()->memberships->countBy('role_id');
                $datasets[$r]['data'][] = $mships[$r] ?? 0;
            }
        }
        $data['datasets'] = $datasets;

        // Log::debug(print_r($data,true));

        return Response::json($data);
    }

    /**
     * members and roles by club for a region
     *
     * @param  \App\Models\Region  $region
     * @return \Illuminate\Http\JsonResponse
     */
    public function game_noreferee_chart(Region $region)
    {
        Log::info('collecting game without referee chart data.', ['region-id' => $region->id]);

        $data = [];
        $data['labels'] = [];
        $datasets = [];
        $datasets[0]['stack'] = 'Stack 1';
        $datasets[0]['label'] = __('region.chart.label.referees.assigned');
        $datasets[0]['data'] = [];
        $datasets[1]['stack'] = 'Stack 1';
        $datasets[1]['label'] = __('region.chart.label.referees.missing');
        $datasets[1]['data'] = [];

        $rs = Game::where('region_id_home', $region->id)->whereNull('referee_1')->orderBy('game_date')->selectRaw('game_date, count(*) as gcnt')->groupBy('game_date')->get();
        $rsbydate = $rs->keyBy('game_date');

        $rs1 = Game::where('region_id_home', $region->id)->whereNotNull('referee_1')->orderBy('game_date')->selectRaw('game_date, count(*) as gcnt')->groupBy('game_date')->get();
        $rs1bydate = $rs1->keyBy('game_date');
        // get all game date
        $alldates = Game::where('region_id_home', $region->id)->orderBy('game_date')->pluck('game_date')->unique();

        // initialize dataset 0
        foreach ($alldates as $gday) {
            $data['labels'][] = Carbon::parse($gday)->isoFormat('L');
            $datasets[0]['data'][] = (isset($rs1bydate[$gday->toDateTimeString()])) ? $rs1bydate[$gday->toDateTimeString()]->gcnt : 0;
            $datasets[1]['data'][] = (isset($rsbydate[$gday->toDateTimeString()])) ? $rsbydate[$gday->toDateTimeString()]->gcnt : 0;
        }

        $data['datasets'] = $datasets;

        // Log::debug(print_r($data,true));

        return Response::json($data);
    }

    /**
     * # club by region
     *
     * @param  \App\Models\Region  $region
     * @return \Illuminate\Http\JsonResponse
     */
    public function region_club_chart(Region $region)
    {
        Log::info('collecting clubs by region data.', ['region-id' => $region->id]);
        $data = [];
        $data['labels'] = [];
        $datasets = [];

        $rs = $region->childRegions()->withCount('clubs', 'leagues', 'gyms', 'teams')->get();
        // initialize datasets

        foreach ($rs as $r) {
            $data['labels'][] = $r->code;
            $datasets[0]['data'][] = $r->clubs_count ?? 0;
            $datasets[1]['data'][] = $r->leagues_count ?? 0;
            $datasets[2]['data'][] = $r->gyms_count ?? 0;
            $datasets[3]['data'][] = $r->teams_count ?? 0;
        }
        $datasets[0]['label'] = trans_choice('club.club', 2);
        $datasets[1]['label'] = trans_choice('league.league', 2);
        $datasets[2]['label'] = trans_choice('gym.gym', 2);
        $datasets[3]['label'] = trans_choice('team.team', 2);

        $data['datasets'] = $datasets;

        Log::debug(print_r($data, true));

        return Response::json($data);
    }

    /**
     * # leaguestates by region
     *
     * @param  \App\Models\Region  $region
     * @return \Illuminate\Http\JsonResponse
     */
    public function region_league_chart(Region $region)
    {
        Log::info('collecting league state by region chart data.', ['region-id' => $region->id]);

        $data = [];
        $data['labels'] = [];
        $datasets = [];

        // get data for sets
        $rs = $region->childRegions;
        $leagues = League::whereIn('region_id', $rs->pluck('id'))->get();

        // initialize datasets
        foreach (LeagueState::getInstances() as $ls) {
            $data['labels'][] = $ls->description;
            $lsset = $leagues->where('state', $ls);
            foreach ($rs as $k => $r) {
                $datasets[$k]['data'][] = $lsset->where('region_id', $r->id)->count();
            }
        }
        foreach ($rs as $k => $r) {
            $datasets[$k]['label'] = $r->code;
        }
        $data['datasets'] = $datasets;

        Log::debug(print_r($data, true));

        return Response::json($data);
    }
}
