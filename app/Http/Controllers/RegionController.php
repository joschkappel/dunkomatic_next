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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Log::info('showing region list');
        return view('region.region_list');
    }

    /**
     * Display a dashboard
     *
     * @return \Illuminate\Http\Response
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


    public function create()
    {
        Log::info('create new region');
        return view('region.region_new');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
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

    public function datatable($language)
    {
        $regions = Region::with('regionadmin')->withCount('clubs', 'leagues', 'teams', 'gyms')->get();

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
            ->editColumn('regionadmin', function ($r) use ($language) {
                if ($r->regionadmin()->exists()) {
                    $admin = $r->regionadmin()->first()->firstname . ' ' . $r->regionadmin()->first()->lastname;
                } else {
                    $admin = '<a href="' . route('membership.region.create', ['language' => Auth::user()->locale, 'region' => $r->id]) . '"><i class="fas fa-plus-circle"></i></a>';
                }
                return $admin;
            })
            ->make(true);
    }

    public function admin_sb()
    {
        $regions = Region::query()->get();

        Log::info('preparing select2 region (with admins) list', ['count' => count($regions)]);
        $response = array();

        foreach ($regions as $region) {
            if ($region->regionadmin()->exists()) {
                $response[] = array(
                    "id" => $region->id,
                    "text" => $region->name
                );
            }
        }

        return Response::json($response);
    }

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
     * Display the specified resource.
     *
     * @param  \App\Region  $region
     * @return \Illuminate\Http\Response
     */
    public function show(Region $region)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Region  $region
     * @return \Illuminate\Http\Response
     */
    public function edit($language, Region $region)
    {
        Log::info('editing region.', ['region-id' => $region->id]);
        $filetypes = ReportFileType::getInstances();
        unset($filetypes[ReportFileType::ICS()->key]);

        return view('region/region_edit', ['region' => $region, 'frequencytype' => JobFrequencyType::getInstances(), 'filetype' => $filetypes ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Region  $region
     * @return \Illuminate\Http\Response
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
            'fmt_club_reports' => 'required|array|min:1',
            'fmt_league_reports.*' => ['required', new EnumValue(ReportFileType::class, false)],
            'close_assignment_at' => 'sometimes|required|date|after:today',
            'close_registration_at' => 'sometimes|required|date|after:close_assignment_at',
            'close_selection_at' => 'sometimes|required|date|after:close_registration_at',
            'close_scheduling_at' => 'sometimes|required|date|after:close_selection_at',
            'close_referees_at' => 'sometimes|required|date|after:close_scheduling_at',
        ]);
        Log::info('region details form data validated OK.');

        $check = $region->update($data);
        $region->refresh();
        Log::notice('region updated.', ['region-id' => $region->id]);

        return redirect()->route('region.dashboard', ['language' => app()->getLocale(), 'region'=>$region]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Region $region
     * @return \Illuminate\Http\Response
     */
    public function destroy(Region $region)
    {
        $region->users()->delete();
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
     * @param Region $region
     * @return Response
     *
     */
    public function league_state_chart(Region $region)
    {

        Log::info('collecting league state chart data.', ['region-id' => $region->id]);

        $data = array();
        $data['labels'] = [];
        $datasets = array();

        $rs = DB::table('leagues')->where('region_id', $region->id)->select('state', DB::raw('count(*) as total'))->groupBy('state')->get();

        // initialize datasets
        foreach (LeagueState::getValues() as $ls) {
            $datasets[0]['stack'] = 'Stack 1';
            $data['labels'][] = LeagueState::getDescription(LeagueState::coerce($ls));
            $datasets[0]['data'][] = $rs->firstWhere('state', $ls)->total ?? 0;
        }

        $data['datasets'] = $datasets;

        // Log::debug(print_r($data,true));

        return Response::json($data);
    }

    /**
     * leagues by age and gender for a region
     *
     * @param Region $region
     * @return Response
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
     * @param Region $region
     * @return Response
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
        $data['labels'] = $rs->pluck('shortname')->unique()->values()->toArray();

        foreach ($rs as $r) {
            $datasets[$r->age_type]['data'][] = $r->total;
        }
        $data['datasets'] = $datasets;

        // Log::debug(print_r($data,true));

        return Response::json($data);
    }

    /**
     * members and roles by club for a region
     *
     * @param Region $region
     * @return Response
     *
     */
    public function club_member_chart(Region $region)
    {
        Log::info('collecting club member chart data.',['region-id'=>$region->id]);
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
        $clubs = $region->clubs->sortBy('shortname');
        $data['labels'] = $clubs->pluck('shortname')->toArray();

        foreach ($clubs as $c) {
            $mships = Membership::whereIn('member_id', $c->members()->pluck('member_id'))
                ->get()
                ->countBy('role_id');

            foreach ($roleList as $r) {
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
     * @param Region $region
     * @return Response
     *
     */
    public function game_noreferee_chart(Region $region)
    {
        Log::info('collecting game without referee chart data.',['region-id'=>$region->id]);

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
            $datasets[0]['data'][] = (isset($rsbydate[$gday->toDateTimeString()])) ? $rsbydate[$gday->toDateTimeString()]->gcnt : 0;
            $datasets[1]['data'][] = (isset($rs1bydate[$gday->toDateTimeString()])) ? $rs1bydate[$gday->toDateTimeString()]->gcnt : 0;
        }

        $data['datasets'] = $datasets;

        // Log::debug(print_r($data,true));

        return Response::json($data);
    }
}
