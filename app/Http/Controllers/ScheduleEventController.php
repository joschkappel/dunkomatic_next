<?php

namespace App\Http\Controllers;

use App\Models\Region;
use App\Models\Schedule;
use App\Models\ScheduleEvent;
use App\Rules\SliderRange;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;

class ScheduleEventController extends Controller
{
    /**
     * Display a ‚listing of the resource.
     *
     * @param  \App\Models\Schedule  $schedule
     * @return \Illuminate\View\View
     */
    public function list(Schedule $schedule)
    {
        Log::info('showing schedule event list.', ['schedule-id' => $schedule->id]);
        $data['schedule'] = $schedule;
        $data['eventcount'] = $schedule->events()->count();

        // calc max events
        $data['eventmax'] = $schedule->max_events;

        return view('schedule/scheduleevent_list', $data);
    }

    /**
     * get listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function datatable(Schedule $schedule)
    {
        // get duplicate dates or overlaps ?
        $duplicates = DB::table('schedule_events')
            ->select(DB::raw('game_date'))
            ->where('schedule_id', $schedule->id)
            ->groupBy('game_date')
            ->havingRaw('COUNT(*) > ?', [1])
            ->pluck('game_date');

        Log::info('checking for duplicate games.', ['schedule-id' => $schedule->id]);

        $events = $schedule->events()->orderBy('game_day', 'ASC')->get();

        Log::info('preapring schedule event list.', ['schedule-id' => $schedule->id]);
        $evlist = datatables()::of($events);

        return $evlist
            ->rawColumns(['game_day', 'game_date'])
            ->addColumn('game_day_sort', function ($event) {
                return $event->game_day;
            })
            ->editColumn('game_day', function ($event) {
                if (Carbon::parse($event->game_date) < Carbon::now()) {
                    return   $event->game_day;
                } else {
                    return '<a href="#" id="eventEditLink" data-id="'.$event->id.
                        '" data-game-day="'.$event->game_day.'" data-weekend="'.$event->full_weekend.'" data-game-date="'.$event->game_date.
                        '">'.$event->game_day.' <i class="fas fa-arrow-circle-right"></i></a>';
                }
            })
            ->editColumn('created_at', function ($event) {
                return $event->created_at->format('d.m.Y H:i');
            })
            ->editColumn('game_date', function ($event) use ($duplicates) {
                $warning = '';
                if ($duplicates->contains(date_format($event->game_date, 'Y-m-d 00:00:00'))) {
                    Log::info('found it');
                    $warning = '  <spawn class="bg-danger">__<i class="fa fa-exclamation-triangle"></i> DUPLICATE <i class="fa fa-exclamation-triangle"></i>__</spawn>';
                }
                if ($event->full_weekend) {
                    $end = Carbon::parse($event->game_date);
                    $end = $end->addDays(1);

                    return $event->game_date->locale(app()->getLocale())->isoFormat('ddd l').' / '.$end->locale(app()->getLocale())->isoFormat('ddd l').$warning;
                } else {
                    return $event->game_date->locale(app()->getLocale())->isoFormat('ddd l').$warning;
                }
            })
            ->make(true);

        //return view('schedule/scheduleevent_list', $data);
    }

    /**
     * Display a calendar listing of the resource.
     *
     * @param  \App\Models\Region  $region
     * @return \Illuminate\Http\JsonResponse
     */
    public function list_cal(Region $region)
    {
        // pass scheduled events back to calendar
        $schedule_ids = $region->schedules()->pluck('id');
        if ($region->is_base_level) {
            Log::notice('adding schedules for top level region.');
            $schedule_ids = $schedule_ids->concat($region->parentRegion->schedules()->pluck('id'));
        }

        $data = ScheduleEvent::whereIn('schedule_id', $schedule_ids)->with('schedule', 'schedule.league_size')->get();
        Log::info('found schedule events.', ['count' => count($data)]);

        $eventlist = [];
        foreach ($data as $event) {
            $end = Carbon::parse($event->game_date)->addDays(1);
            $start = Carbon::parse($event->game_date)->addDays(1);

            if ($event->full_weekend) {
                //  if ( $end->dayOfWeek == 6 ){
                $end = $end->addDays(2);
                // }
            }

            if ($event->schedule->region->is($region)) {
                $title = '( '.$event->game_day.' ) '.$event->schedule->name;
            } else {
                $title = '( '.$event->game_day.' ) ('.$event->schedule->region->code.')  '.$event->schedule->name;
            }
            $eventlist[] = ['title' => $title, 'start' => $start, 'end' => $end, 'allDay' => true, 'color' => $event->schedule->color ?? 'green'];
        }
        Log::info('preparing event list.', ['event count' => count($eventlist)]);

        return Response::json($eventlist);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Schedule  $schedule
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, Schedule $schedule)
    {
        $data = $request->validate([
            'startdate' => 'required|date|after:'.Carbon::now()->addDays(30),
        ]);
        Log::info('create schedule event form data validated OK.');

        // check if 2* or 3* size)
        $size = $schedule->league_size->size;
        $repeat = $schedule->iterations;

        $gamedays = (($size - 1) * 2 * $repeat);
        $startdate = CarbonImmutable::parse($data['startdate']);
        $startweekend = $startdate->endOfWeek(Carbon::SATURDAY);

        Log::notice('create schedule events.', ['schedule-id' => $schedule->id, 'count-days' => $gamedays, 'start-date' => $startdate, 'start-weekend' => $startweekend]);

        for ($i = 1; $i <= $gamedays; $i++) {
            $gamedate = $startweekend;
            //    Log::debug($i.' ->  '.$startweekend.' -> '.$gamedate->addWeeks($i-1)->startOfDay());

            $ev = new ScheduleEvent;
            $ev->schedule_id = $schedule->id;
            $ev->game_day = $i;
            $ev->game_date = $gamedate->addWeeks($i - 1)->startOfDay();
            $ev->full_weekend = true;
            $ev->save();
        }

        return redirect()->action([ScheduleEventController::class, 'list'], ['schedule' => $schedule]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Schedule  $schedule
     * @return \Illuminate\Http\RedirectResponse
     */
    public function clone(Request $request, Schedule $schedule)
    {
        $data = $request->validate([
            'clone_from_schedule' => 'required|exists:schedules,id',
        ]);
        Log::info('clone schedule event form data validated OK.');

        $from_schedule = Schedule::findOrFail($data['clone_from_schedule']);
        $from_events = $from_schedule->events()->get();

        Log::notice('clone schedule events.', ['schedule-id' => $schedule->id, 'from-schedule-id' => $from_schedule->id]);
        //Log::debug(print_r($from_events,true));
        foreach ($from_events as $from_event) {
            $to_event = $from_event->replicate();
            $to_event->schedule_id = $schedule->id;
            $to_event->save();
        }

        return redirect()->action([ScheduleEventController::class, 'list'], ['schedule' => $schedule]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Schedule  $schedule
     * @return \Illuminate\Http\RedirectResponse
     */
    public function shift(Request $request, Schedule $schedule)
    {
        $data = $request->validate([
            'direction' => 'required|in:+,-',
            'unit' => 'required|in:DAY,WEEK,MONTH,YEAR',
            'unitRange' => 'required|integer|between:1,12',
            'gamedayRange' => ['required', new SliderRange(1, $schedule->max_events)],
        ]);
        Log::info('shift schedule events form data validated OK.');

        $min_gameday = explode(';', $data['gamedayRange'])[0];
        $max_gameday = explode(';', $data['gamedayRange'])[1];

        if ($data['direction'] === '+') {
            $schedule->events()->whereBetween('game_day', [$min_gameday, $max_gameday])->update(['game_date' => DB::raw('DATE_ADD(game_date, INTERVAL '.$data['unitRange'].' '.$data['unit'].')')]);
            Log::notice('shifting schedule events to the future.', ['schedule-id' => $schedule->id, 'game-days' => $data['gamedayRange'], 'shift-by' => $data['unitRange'].' '.$data['unitRange']]);
        } else {
            $schedule->events()->whereBetween('game_day', [$min_gameday, $max_gameday])->update(['game_date' => DB::raw('DATE_SUB(game_date, INTERVAL '.$data['unitRange'].' '.$data['unit'].')')]);
            Log::notice('shifting schedule events to the past.', ['schedule-id' => $schedule->id, 'game-days' => $data['gamedayRange'], 'shift-by' => $data['unitRange'].' '.$data['unitRange']]);
        }
        $schedule->refresh();

        return redirect()->action([ScheduleEventController::class, 'list'], ['schedule' => $schedule]);
    }

    /**
     * Remove game days from schedule events.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Schedule  $schedule
     * @return \Illuminate\Http\RedirectResponse
     */
    public function remove(Request $request, Schedule $schedule)
    {
        $data = $request->validate([
            'gamedayRemoveRange' => ['required', new SliderRange(1, $schedule->max_events)],
        ]);
        Log::info('remove schedule events form data validated OK.');

        $min_gameday = explode(';', $data['gamedayRemoveRange'])[0];
        $max_gameday = explode(';', $data['gamedayRemoveRange'])[1];

        $schedule->events()->whereBetween('game_day', [$min_gameday, $max_gameday])->delete();
        Log::notice('removing schedule events.', ['schedule-id' => $schedule->id, 'game-days' => $data['gamedayRemoveRange']]);
        $schedule->refresh();

        return redirect()->action([ScheduleEventController::class, 'list'], ['schedule' => $schedule]);
    }

    /**
     * Add game days to schedule events.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Schedule  $schedule
     * @return \Illuminate\Http\RedirectResponse
     */
    public function add(Request $request, Schedule $schedule)
    {
        $data = $request->validate([
            'gamedayAddRange' => ['required', new SliderRange(1, $schedule->max_events)],
        ]);
        Log::info('add schedule events form data validated OK.');

        $min_gameday = explode(';', $data['gamedayAddRange'])[0];
        $max_gameday = explode(';', $data['gamedayAddRange'])[1];

        // get missgin games days in above range
        $all_events = collect(range($min_gameday, $max_gameday));
        $missing_events = $all_events->diff($schedule->events->pluck('game_day'));

        // $schedule->events()->whereBetween('game_day', [$min_gameday, $max_gameday])->delete();
        Log::notice('adding schedule events.', ['schedule-id' => $schedule->id, 'game-day-range' => $data['gamedayAddRange'], 'added-days' => $missing_events]);
        $startdate = $schedule->events->where('game_day', 1)->first()->game_date ?? now();
        $startdate = CarbonImmutable::parse($startdate);
        $startweekend = $startdate->endOfWeek(Carbon::SATURDAY);

        foreach ($missing_events as $me) {
            $gamedate = $startweekend;
            $ev = new ScheduleEvent;
            $ev->schedule_id = $schedule->id;
            $ev->game_day = $me;
            $ev->game_date = $gamedate->addWeeks($me - 1)->startOfDay();
            $ev->full_weekend = true;
            $ev->save();
        }
        $schedule->refresh();

        return redirect()->action([ScheduleEventController::class, 'list'], ['schedule' => $schedule]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ScheduleEvent  $schedule_event
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, ScheduleEvent $schedule_event)
    {
        $data = $request->validate([
            'full_weekend' => 'required|boolean',
            'game_date' => 'required|date|after:today',
        ]);
        Log::info('schedule event form data validated OK.');

        $data['game_date'] = CarbonImmutable::parse($data['game_date']);

        $check = $schedule_event->update($data);
        Log::notice('schedule event updated.', ['schedule-id' => $schedule_event->schedule->id, 'scheduleevent-id' => $schedule_event->id]);

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Schedule  $schedule
     * @return \Illuminate\Http\RedirectResponse
     */
    public function list_destroy(Schedule $schedule)
    {
        $schedule->events()->delete();
        Log::notice('schedule events deleted.', ['schedule-id' => $schedule->id]);

        return redirect()->action([ScheduleEventController::class, 'list'], ['schedule' => $schedule]);
    }
}
