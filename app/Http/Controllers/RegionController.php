<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;

use BenSampo\Enum\Rules\EnumValue;
use App\Enums\JobFrequencyType;
use App\Enums\LeagueState;
use App\Enums\LeagueAgeType;
use App\Enums\LeagueGenderType;
use App\Enums\Role;
use App\Enums\ReportFileType;

use App\Models\Region;
use App\Models\Member;
use App\Models\Membership;
use App\Models\Game;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;

use Carbon\Carbon;
use Silber\Bouncer\BouncerFacade as Bouncer;
use Datatables;

class RegionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     *
     */
    public function index()
    {
        Log::info('showing region list');
        return view('region.region_list');
    }

    /**
     * Display a dashboard
     *
     * @param Request $request
     * @param string $language
     * @param \App\Models\Region $region
     * @return \Illuminate\View\View
     *
     */
    public function dashboard(Request $request, $language, Region $region)
    {
        if (!Bouncer::canAny(['create-regions', 'update-regions'])) {
            Log::warning('[ACCESS DENIED]', ['url' => $request->path(), 'ip' => $request->ip()]);
            abort(403);
        }
        $data['region'] = Region::withCount('clubs', 'gyms', 'teams', 'leagues', 'childRegions')->find($region->id);
        $data['members'] = Member::whereIn('id', $region->members()->pluck('member_id'))->with('memberships')->get();
        $data['member_count'] = $region->clubs()->with('members')->get()->pluck('members.*.id')->flatten()->concat(
            $region->leagues()->with('members')->get()->pluck('members.*.id')->flatten()
        )->concat(
            $region->members->pluck('id')->flatten()
        )->unique()->count();

        $data['games_count'] = $region->clubs()->with('games_home')->get()->pluck('games_home.*.id')->flatten()->count();
        $data['games_noref_count'] = $region->clubs()->with('games_noreferee')->get()->pluck('games_noreferee.*.id')->flatten()->count();

        Log::info('showing region dashboard', ['region-id' => $region->id]);
        return view('region.region_dashboard', $data);
    }


    /**
     * Display a brief overview
     *
     * @param string $language
     * @param \App\Models\Region $region
     * @return \Illuminate\View\View
     */
    public function briefing($language, Region $region)
    {
        $data['region'] = $region;


        $data['memberships'] = $region->memberships()->with('member')->get();
        $c_members = collect();
        foreach ($region->clubs->sortBy('shortname') as $c) {
            $c_members = $c_members->concat($c->members()->wherePivot('role_id', Role::ClubLead())->get());
        }
        $data['clubs'] = $c_members;
        $l_members = collect();
        foreach ($region->leagues->sortBy('shortname') as $l) {
            $l_members = $l_members->concat($l->members()->wherePivot('role_id', Role::LeagueLead())->get());
        }
        $data['leagues'] = $l_members;

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
     *
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'region_id' => 'sometimes|exists:regions,id',
            'name' => 'required',
            'code'  => 'required',
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
     * @param  string $language
     * @return \Illuminate\Http\JsonResponse
     *
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
                if (Bouncer::canAny(['create-regions', 'update-regions'])) {
                    return '<a href="' . route('region.dashboard', ['language' => Auth::user()->locale, 'region' => $data->id]) . '">' . $data->code . '</a>';
                } else {
                    return $data->code;
                }
            })
            ->editColumn('regionadmin', function ($r) {
                if ($r->regionadmins()->exists()) {
                    $admin = $r->regionadmins()->first()->firstname . ' ' . $r->regionadmins()->first()->lastname;
                } else {
                    $admin = '<a href="' . route('membership.region.create', ['language' => Auth::user()->locale, 'region' => $r->id]) . '"><i class="fas fa-plus-circle"></i></a>';
                }
                return $admin;
            })
            ->make(true);
    }

    /**
     * select2 list with all regions
     *
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function admin_sb()
    {
        $regions = Region::all();

        Log::info('preparing select2 region (with admins) list', ['count' => count($regions)]);
        $response = array();

        foreach ($regions as $region) {
            if ($region->regionadmins()->exists()) {
                $response[] = array(
                    "id" => $region->id,
                    "text" => $region->name
                );
            }
        }

        return Response::json($response);
    }

    /**
     * select2 list with all top level regions
     *
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function hq_sb()
    {
        $regions = Region::whereNull('hq')->get();

        Log::info('preparing select2 top region list', ['count' => count($regions)]);
        $response = array();

        foreach ($regions as $region) {
            $response[] = array(
                "id" => $region->id,
                "text" => $region->name
            );
        }

        return Response::json($response);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param string $language
     * @param \App\Models\Region $region
     * @return \Illuminate\View\View
     *
     */
    public function edit($language, Region $region)
    {
        Log::info('editing region.', ['region-id' => $region->id]);
        $filetypes = ReportFileType::getInstances();
        unset($filetypes[ReportFileType::ICS()->key]);

        return view('region/region_edit', ['region' => $region, 'frequencytype' => JobFrequencyType::getInstances(), 'filetype' => $filetypes]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Region  $region
     * @return \Illuminate\Http\RedirectResponse
     *
     */
    public function update_details(Request $request, Region $region)
    {
        $data = $request->validate([
            'name' => 'required|max:40',
            'game_slot' => 'required|integer|in:60,75,90,105,120,135,150',
            'job_noleads' => ['required', new EnumValue(JobFrequencyType::class, false)],
            'job_game_notime' => ['required', new EnumValue(JobFrequencyType::class, false)],
            'job_game_overlaps' => ['required', new EnumValue(JobFrequencyType::class, false)],
            'job_email_valid' => ['required', new EnumValue(JobFrequencyType::class, false)],
            'job_league_reports' => ['required', new EnumValue(JobFrequencyType::class, false)],
            'job_club_reports' => ['required', new EnumValue(JobFrequencyType::class, false)],
            'fmt_club_reports' => 'required|array|min:1',
            'fmt_club_reports.*' => ['required', new EnumValue(ReportFileType::class, false)],
            'fmt_league_reports' => 'required|array|min:1',
            'fmt_league_reports.*' => ['required', new EnumValue(ReportFileType::class, false)],
            'close_assignment_at' => 'sometimes|required|date|after:today',
            'close_registration_at' => 'sometimes|required|date|after:close_assignment_at',
            'close_selection_at' => 'sometimes|required|date|after:close_registration_at',
            'close_scheduling_at' => 'sometimes|required|date|after:close_selection_at',
            'close_referees_at' => 'sometimes|required|date|after:close_scheduling_at'
        ]);
        Log::info('region details form data validated OK.');
        $auto_state_change = $request->input('auto_state_change');
        if (isset($auto_state_change) and ($auto_state_change == 'on')) {
            $data['auto_state_change'] = true;
        } else {
            $data['auto_state_change'] = false;
        }

        $check = $region->update($data);
        $region->refresh();
        Log::notice('region updated.', ['region-id' => $region->id]);

        return redirect()->route('region.dashboard', ['language' => app()->getLocale(), 'region' => $region]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Region $region
     * @return \Illuminate\Http\RedirectResponse
     *
     */
    public function destroy(Region $region)
    {
        foreach ($region->users() as $u){
            $u->delete();
        }
        Log::info('region users deleted', ['region-id' => $region->id]);

        $region->schedules()->delete();
        Log::info('region schedules deleted', ['region-id' => $region->id]);

        // $region->messages()->delete();
        $region->members()->delete();
        Log::info('region members deleted', ['region-id' => $region->id]);

        $region->memberships()->delete();
        Log::info('region memberships deleted', ['region-id' => $region->id]);

        $region->delete();
        Log::notice('region deleted', ['region-id' => $region->id]);

        return redirect()->route('region.index', app()->getLocale());
    }

    /**
     * leagues by status for a region
     *
     * @param \App\Models\Region $region
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function league_state_chart(Region $region)
    {

        Log::info('collecting league state chart data.', ['region-id' => $region->id]);

        $data = array();
        $data['labels'] = [];
        $datasets = array();
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
     * @param \App\Models\Region $region
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function league_socio_chart(Region $region)
    {
        Log::info('collecting league social chart data.', ['region-id' => $region->id]);
        $data = array();
        $data['labels'] = [];
        $datasets = array();

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
     * @param \App\Models\Region $region
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function club_team_chart(Region $region)
    {

        Log::info('collecting club teams chart data.', ['region-id' => $region->id]);
        $data = array();
        $data['labels'] = [];
        $datasets = array();
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
        $select = "select c.shortname, l.age_type, count(l.age_type) as total ";
        $select .= " FROM clubs as c, teams as t, leagues as l ";
        $select .= " WHERE c.region_id = " . $region->id;
        $select .= " AND t.club_id = c.id ";
        $select .= " AND t.league_id = l.id ";
        $select .= " GROUP BY c.shortname, l.age_type";
        $select .= " ORDER BY c.shortname ASC, l.age_type ASC";

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
     * @param \App\Models\Region $region
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function club_member_chart(Region $region)
    {
        Log::info('collecting club member chart data.', ['region-id' => $region->id]);
        $data = array();
        $data['labels'] = [];
        $datasets = array();
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
     * @param \App\Models\Region $region
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function game_noreferee_chart(Region $region)
    {
        Log::info('collecting game without referee chart data.', ['region-id' => $region->id]);

        $data = array();
        $data['labels'] = [];
        $datasets = array();
        $datasets[0]['stack'] = 'Stack 1';
        $datasets[0]['label'] = __('region.chart.label.referees.assigned');
        $datasets[0]['data'] = [];
        $datasets[1]['stack'] = 'Stack 1';
        $datasets[1]['label'] = __('region.chart.label.referees.missing');
        $datasets[1]['data'] = [];

        $clubs = $region->clubs()->pluck('id');
        $rs = Game::whereIn('club_id_home', $clubs)->whereNull('referee_1')->orderBy('game_date')->selectRaw('game_date, count(*) as gcnt')->groupBy('game_date')->get();
        $rsbydate = $rs->keyBy('game_date');

        $rs1 = Game::whereIn('club_id_home', $clubs)->whereNotNull('referee_1')->orderBy('game_date')->selectRaw('game_date, count(*) as gcnt')->groupBy('game_date')->get();
        $rs1bydate = $rs1->keyBy('game_date');
        // get all game date
        $alldates = Game::whereIn('club_id_home', $clubs)->orderBy('game_date')->pluck('game_date')->unique();

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
}
