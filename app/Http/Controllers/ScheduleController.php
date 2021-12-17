<?php

namespace App\Http\Controllers;

use App\Models\LeagueSize;
use App\Models\Schedule;
use App\Models\Region;
use App\Models\League;

use Illuminate\Http\Request;

use Datatables;
use Silber\Bouncer\BouncerFacade as Bouncer;
use Illuminate\Support\Carbon;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($language, Region $region)
    {
        Log::info('showing schedule list.');
        return view('schedule/schedule_list', ['region' => $region]);
    }
    /**
     * Display a listing to compare multiple resources
     *
     * @return \Illuminate\Http\Response
     */
    public function compare($language, Region $region)
    {
        if ($region->is_base_level) {
            Log::notice('getting schedules for base level region');
            $schedules = Schedule::whereIn('region_id', [$region->id, $region->parentRegion->id])->orderBy('region_id', 'ASC')->get();
            $parentRegion = $region->parentRegion;
        } else {
            Log::notice('getting schedules for top level region');
            $schedules = $region->schedules()->orderBy('region_id', 'ASC')->get();
            $parentRegion = $region;
        }


        return view('schedule/schedules_list', ['region' => $region, 'hq' => $parentRegion, 'schedules' => $schedules, 'language' => $language]);
    }

    /**
     * Display a listing to compare multiple resources
     *
     * @return \Illuminate\Http\Response
     */
    public function compare_datatable($language, Region $region)
    {
        Log::info('retrieving schedules.');
        if ($region->is_base_level) {
            $schedules = Schedule::whereIn('region_id', [$region->id, $region->parentRegion->id])->orderBy('region_id', 'ASC')->get();
        } else {
            $schedules = $region->schedules()->orderBy('region_id', 'ASC')->get();
        }
        Log::info('schedules found.', ['schedules' => $schedules->pluck('id')]);

        $select = "game_date, full_weekend ";
        foreach ($schedules as $s) {
            $select .= ", max(case when (schedule_id=" . $s->id . " and full_weekend) then game_day when (schedule_id=" . $s->id . " and not full_weekend) then CONCAT(game_day,'(*)') else ' ' end ) as 's_" . $s->id . "' ";
        }
        // $select .= "FROM schedule_events WHERE schedule_id in (".$ids.") GROUP BY game_date";

        $events = DB::table('schedule_events')
            ->select(DB::raw($select))
            ->whereIn('schedule_id', $schedules->pluck('id'))
            ->groupBy(['game_date', 'full_weekend'])
            ->get();
        Log::info('game days found.', ['count' => $events->count()]);

        Log::info('preparing schedule comparison list');
        $elist = datatables()::of($events);
        $curYear = '';
        return $elist
            ->addColumn('sat_game', function ($e) use ($language) {
                $gdate = Carbon::parse($e->game_date);
                if ($gdate->isSaturday()) {
                    return $gdate->locale($language)->isoFormat('ddd L');
                }
            })
            ->addColumn('sun_game', function ($e) use ($language) {
                $gdate = Carbon::parse($e->game_date);
                if ($gdate->isSaturday() and $e->full_weekend) {
                    return $gdate->addDay()->locale($language)->isoFormat('ddd L');
                } elseif (!$e->full_weekend) {
                    return $gdate->locale($language)->isoFormat('ddd L');
                }
            })
            ->addColumn('year', function ($e) use (&$curYear) {
                if ($curYear != Carbon::parse($e->game_date)->isoFormat('YYYY')) {
                    $curYear = Carbon::parse($e->game_date)->isoFormat('YYYY');
                    return $curYear;
                } else {
                    return '';
                }
            })
            ->make(true);
    }


    /**
     * Display a listing of the resource for selecbboxes
     *
     * @return \Illuminate\Http\Response
     */
    public function sb_region(Region $region)
    {
        if ($region->is_base_level) {
            Log::notice('getting schedules for base level region');
            $schedules = Schedule::whereIn('region_id', [$region->id, $region->parentRegion->id])->orderBy('name', 'ASC')->get();
        } else {
            Log::notice('getting schedules for top level region');
            $schedules = $region->schedules()->orderBy('name', 'ASC')->get();
        }

        Log::info('preparing select2 schedule list.', ['count' => count($schedules)] );
        $response = array();

        foreach ($schedules as $schedule) {
            if ($schedule->region->is($region)) {
                $response[] = array(
                    "id" => $schedule->id,
                    "text" => $schedule->name
                );
            } else {
                $response[] = array(
                    "id" => $schedule->id,
                    "text" => '(' . $schedule->region->code . ') ' . $schedule->name
                );
            }
        }
        return Response::json($response);
    }

    /**
     * Display a listing of the resource for selecbboxes
     *
     * @return \Illuminate\Http\Response
     */
    public function sb_region_size(Region $region, LeagueSize $size)
    {
        $schedules = $region->schedules()
            ->where(function (Builder $query) use ($size) {
                return $query->where('league_size_id', $size->id)
                    ->orWhere('league_size_id', LeagueSize::UNDEFINED);
            })
            ->orderBy('name', 'ASC')->get();


        Log::info('preparing select2 schedule for leaguesize list.', ['leaguesize-id'=>$size->id, 'count' => count($schedules)] );
        $response = array();

        foreach ($schedules as $schedule) {
            $response[] = array(
                "id" => $schedule->id,
                "text" => $schedule->name
            );
        }

        return Response::json($response);
    }

    /**
     * Display a listing of the resource for selecbboxes
     *
     * @return \Illuminate\Http\Response
     */
    public function sb_size(Schedule $schedule)
    {

        $schedules = Schedule::where('id', '!=', $schedule->id)
            ->where('league_size_id', $schedule->league_size_id)
            ->where('iterations', $schedule->iterations)
            ->orderBy('name', 'ASC')
            ->get();

        Log::info('preparing select2 matching schedules list.', ['schedule-id'=>$schedule->id, 'count' => count($schedules)] );
        $response = array();

        foreach ($schedules as $schedule) {
            $response[] = array(
                "id" => $schedule->id,
                "text" => $schedule->name
            );
        }

        return Response::json($response);
    }
    /**
     * Display a listing of the resource .
     *
     * @return \Illuminate\Http\Response
     */
    public function list(Region $region)
    {
        $schedule = $region->schedules()->with('league_size')->withCount('events')->get();

        Log::info('preparing schedule list');
        $stlist = datatables()::of($schedule);

        return $stlist
            ->addIndexColumn()
            ->addColumn('action', function ($data) {
                if (!$data->leagues()->exists()) {
                    $btn = '<button type="button" id="deleteSchedule" name="deleteSchedule" class="btn btn-outline-danger btn-sm" data-schedule-id="' . $data->id . '"
                    data-schedule-name="' . $data->name . '" data-events="' . count($data->events) . '" data-toggle="modal" data-target="#modalDeleteSchedule"><i class="fa fa-trash"></i></button>';
                    return $btn;
                } else {
                    return '';
                }
            })
            ->addColumn('color', function ($data) {
                return '<spawn style="background-color:' . $data->eventcolor . '">' . $data->eventcolor . '</div>';
            })
            ->addColumn('used_by_leagues', function ($data) {
                return $data->leagues->pluck('shortname')->implode(', ');
            })
            ->addColumn('events', function ($data) {
                if ($data->custom_events) {
                    return __('Custom');
                } else {
                    if ((League::where('schedule_id', $data->id)->has('games')->count() == 0) and (Bouncer::can(['update-schedules']))) {
                        return '<a href="' . route('schedule_event.list', $data) . '">' . $data->events_count . ' <i class="fas fa-arrow-circle-right"></i></a>';
                    } else {
                        return $data->events_count;
                    }
                }
            })
            ->rawColumns(['name', 'color', 'events', 'action'])
            ->editColumn('created_at', function ($user) {
                return $user->created_at->format('d.m.Y H:i');
            })
            ->editColumn('name', function ($data) {
                if (Bouncer::canAny(['create-schedules', 'update-schedules'])) {
                    return '<a href="' . route('schedule.edit', ['language' => Auth::user()->locale, 'schedule' => $data->id]) . '">' . $data->name . ' <i class="fas fa-arrow-circle-right"></i></a>';
                } else {
                    return $data->name;
                }
            })
            ->editColumn('iterations', function ($data) {
                if ($data->iterations == 1) {
                    return __('schedule.single');
                } elseif ($data->iterations == 2) {
                    return __('schedule.double');
                } elseif ($data->iterations == 3) {
                    return __('schedule.triple');
                } else {
                    return "????";
                }
            })
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($language, Region $region)
    {
        Log::info('create new schedule.');
        return view('schedule/schedule_new', ['region' => $region]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate(Schedule::$createRules);
        Log::info('schedule form data validated OK.');

        if ($request['custom_events'] == 'on') {
            $data['custom_events'] = true;
            $data['league_size_id'] = LeagueSize::UNDEFINED;
        } else {
            $data['custom_events'] = false;
        }

        $schedule = Schedule::create($data);
        Log::notice('new schedule created.', ['schedule-id'=>$schedule->id]);

        return redirect()->route('schedule.index', ['language' => app()->getLocale(), 'region' => $schedule->region]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function show(Schedule $schedule)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function edit($language, Schedule $schedule)
    {
        Log::info('editing schedule.', ['schedule-id'=>$schedule->id]);
        return view('schedule/schedule_edit', ['schedule' => $schedule]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Schedule $schedule)
    {
        $data = $request->validate(Schedule::$updateRules);
        Log::info('schedule form data validated OK.');

        if ($request['custom_events'] == 'on') {
            $data['custom_events'] = true;
            $data['league_size_id'] = LeagueSize::UNDEFINED;
        } else {
            $data['custom_events'] = false;
        }

        $check = $schedule->update($data);
        $schedule->refresh();
        Log::notice('schedule updated.', ['schedule-id'=> $schedule->id]);

        return redirect()->route('schedule.index', ['language' => app()->getLocale(), 'region' => $schedule->region]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Schedule $schedule
     * @return \Illuminate\Http\Response
     */
    public function destroy(Schedule $schedule)
    {
        $schedule->events()->delete();
        Log::info('schedule events deleted.',['schedule-id'=>$schedule->id]);

        $schedule->delete();
        Log::notice('schedule deleted.',['schedule-id'=>$schedule->id]);

        return redirect()->route('schedule.index', ['language' => app()->getLocale(), 'region' => $schedule->region]);
    }
}
