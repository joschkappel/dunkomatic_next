<?php

namespace App\Http\Controllers;

use App\Models\ScheduleEvent;
use App\Models\Schedule;
use App\Models\Region;
use Illuminate\Http\Request;
use Datatables;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;

use App\Rules\SliderRange;

use Carbon\Carbon;
use Carbon\CarbonImmutable;

class ScheduleEventController extends Controller
{

    /**
     * get pivot table view.
       select game_date,
                 max(case when schedule_id = '1' then game_day else ' '  end) as '1',
                 max(case when schedule_id = '2' then game_day else ' '  end) as '2',
                 max(case when schedule_id = '5' then game_day else ' '  end) as '5'
               from schedule_events
               where schedule_id in (1,2,5)
               group by game_date
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function list_piv(Request $request)
    {
        Log::debug(print_r($request->input('selVals'),true));

        $selSchedules = $request->input('selVals');

        $select = "select date_format(game_date, '%a %d.%b.%Y') as '".__('game.game_date')."' ";
        $where =  array();
        $cols = array();

        if ( $request->input('selVals') ){
          foreach ($selSchedules as $i => $item){
            // Log::debug(print_r($selSchedules[$i]['id'],true));
            $cols[] = 'max(case when schedule_id = '.$selSchedules[$i]['id'].' then game_day else " " end) as "'.$selSchedules[$i]['text'].'"';
            $where[] = $selSchedules[$i]['id'];
          }

          $select .= ', '.implode(' ,', $cols).' from schedule_events where schedule_id in ('.implode(' ,', $where).') group by game_date';
        } else {
          $select .= ' from schedule_events group by game_date';
        }

        Log::debug($select);
        $events = collect(DB::select($select));
        $returnhtml =  view("schedule/includes/scheduleevent_pivot", ["events" => $events])->render();
        //Log::debug(print_r($returnhtml, true));
        return Response::json($returnhtml);

    }

    /**
     * Display a ‚listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list(Schedule $schedule )
    {
      $data['schedule'] = $schedule;
      $data['eventcount'] = $schedule->events()->count();

      return view('schedule/scheduleevent_list', $data);
    }

    /**
     * get listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function datatable(Schedule $schedule )
    {
      // get duplicate dates or overlaps ?
      $duplicates = DB::table('schedule_events')
                     ->select(DB::raw("game_date"))
                     ->where('schedule_id', $schedule->id)
                     ->groupBy('game_date')
                     ->havingRaw('COUNT(*) > ?', [1])
                     ->pluck('game_date');

      Log::debug(print_r($duplicates,true));

      $events = $schedule->events()->orderBy('game_day','ASC')->get();
      $evlist = datatables()::of($events);

      return $evlist
        ->rawColumns(['game_day','game_date'])
        ->addColumn('game_day_sort', function($event){
          return $event->game_day;
        })
        ->editColumn('game_day', function($event){
            if (Carbon::parse($event->game_date) < Carbon::now()){
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
                $warning='';
                if ($duplicates->contains(date_format($event->game_date, 'Y-m-d 00:00:00' ))){
                  Log::info('found it');
                  $warning = '  <spawn class="bg-danger">__<i class="fa fa-exclamation-triangle"></i> DUPLICATE <i class="fa fa-exclamation-triangle"></i>__</spawn>';
                };
                if ( $event->full_weekend){
                  $end = Carbon::parse($event->game_date);
                  $end = $end->addDays(1);
                  return $event->game_date->locale(app()->getLocale())->isoFormat('ddd l').' / '. $end->locale(app()->getLocale())->isoFormat('ddd l').$warning;
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
     * @return \Illuminate\Http\Response
     */
    public function list_cal(Region $region)
    {
        Log::info('getting schedule events');
        // pass scheduled events back to calendar
        $schedule_ids = $region->schedules()->pluck('id');

        $data = ScheduleEvent::whereIn('schedule_id', $schedule_ids)->get();
        Log::debug('found schedule events: '.count($data));

        $eventlist = array();
        foreach( $data as $event){
          if ( $event->full_weekend ){
            $end = Carbon::parse($event->game_date);
            if ( $end->dayOfWeek == 6 ){
              $end = $end->addDays(2);
            }
          } else {
            $end = $event->game_date;
          }
          $title = '( '.$event->game_day.' ) '.$event->schedule->name;
          $eventlist[] = array( "title" => $title, "start" => $event->game_date, "end" => $end, "allDay" => true, "color" => $event->schedule->eventcolor );
        }
        Log::info('event list with '.count($eventlist));

        return Response::json($eventlist);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Schedule $schedule)
    {
        Log::debug($request->input());
        $data = $request->validate([
            'startdate' => 'required|date|after:'.Carbon::now()->addDays(30),
          ]);

        // check if 2* or 3* size)
        $size = $schedule->league_size->size;
        $repeat = $schedule->league_size->iterations;

        $gamedays = ( ($size - 1) * 2 * $repeat);
        $startdate = CarbonImmutable::parse($data['startdate']);
        $startweekend = $startdate->endOfWeek(Carbon::SATURDAY);
        Log::info('need to create '.$gamedays.' events starting from '.$startdate.' / '.$startweekend);

        for ($i = 1; $i <= $gamedays; $i++){
          $gamedate = $startweekend;
      //    Log::debug($i.' ->  '.$startweekend.' -> '.$gamedate->addWeeks($i-1)->startOfDay());

          $ev = new ScheduleEvent;
          $ev->schedule_id = $schedule->id;
          $ev->game_day = $i;
          $ev->game_date = $gamedate->addWeeks($i-1)->startOfDay();
          $ev->full_weekend = true;
          $ev->save();

        }
        return redirect()->action('ScheduleEventController@list', ['schedule' => $schedule]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function clone(Request $request, Schedule $schedule)
    {
        Log::debug(print_r($request->all(),true));
        $data = $request->validate([
            'clone_from_schedule' => 'required|exists:schedules,id',
          ]);

        $from_events = Schedule::find($data['clone_from_schedule'])->events()->get();

        //Log::debug(print_r($from_events,true));
        foreach ($from_events as $from_event){
          $to_event = $from_event->replicate();
          $to_event->schedule_id = $schedule->id;
          $to_event->save();
        }
        return redirect()->action('ScheduleEventController@list', ['schedule' => $schedule]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function shift(Request $request, Schedule $schedule)
    {
        Log::debug($request->input());
        $data = $request->validate([
            'direction' => 'required|in:+,-',
            'unit' => 'required|in:DAY,WEEK,MONTH,YEAR',
            'unitRange' => 'required|integer|between:1,12',
            'gamedayRange' => array( 'required', new SliderRange(1, 10) ),
          ]);
        Log::debug($data);

        $min_gameday = explode(";", $data['gamedayRange'])[0];
        $max_gameday = explode(";", $data['gamedayRange'])[1];

        if ( $data['direction'] === '+'){
          $schedule->events()->whereBetween('game_day', [$min_gameday, $max_gameday])->update(['game_date' => DB::raw('DATE_ADD(game_date, INTERVAL '.$data['unitRange'].' '.$data['unit'].')')]);
        } else {
          $schedule->events()->whereBetween('game_day', [$min_gameday, $max_gameday])->update(['game_date' => DB::raw('DATE_SUB(game_date, INTERVAL '.$data['unitRange'].' '.$data['unit'].')')]);
        }
        $schedule->refresh();

        return redirect()->action('ScheduleEventController@list', ['schedule' => $schedule]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ScheduleEvent $schedule_event)
    {
        Log::debug(print_r($request->all(),true));

        $data = $request->validate( [
            'full_weekend' => 'required|boolean',
            'game_date' => 'required|date|after:today',
        ]);

        $data['game_date'] = CarbonImmutable::parse($data['game_date']);

        Log::debug(print_r($data,true));
        $check = ScheduleEvent::where('id', $schedule_event->id)->update($data);
        return redirect()->back();

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function list_destroy(Schedule $schedule)
    {
      Log::info('deleting events for  '.print_r($schedule->id,true));
      $schedule->events()->delete();
      return redirect()->action('ScheduleEventController@list', ['schedule' => $schedule]);
    }
}
