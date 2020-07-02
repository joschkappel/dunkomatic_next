<?php

namespace App\Http\Controllers;

use App\ScheduleEvent;
use App\Schedule;
use Illuminate\Http\Request;
use Datatables;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;

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
     * @param  \App\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function list_piv(Request $request)
    {
        Log::debug(print_r($request->input('selVals'),true));

        $selSchedules = $request->input('selVals');

        $select = "select date_format(game_date, '%a %d.%b.%Y') as 'Game Date' ";
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
        $returnhtml =  view("schedule/scheduleevent_pivot", ["events" => $events])->render();
        //Log::debug(print_r($returnhtml, true));
        return Response::json($returnhtml);

    }

    /**
     * Display a ‚listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list( $id )
    {
      $data['schedule'] = Schedule::find($id);
      $data['eventcount'] = ScheduleEvent::query()->where('schedule_id', $id)->count();

      return view('schedule/scheduleevent_list', $data);
    }

    /**
     * get listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list_dt( $id )
    {
      $events = ScheduleEvent::query()->where('schedule_id', $id)->get();
      $evlist = datatables::of($events);

      return $evlist
        ->addIndexColumn()
        ->editColumn('created_at', function ($event) {
                return $event->created_at->format('d.m.Y H:i');
            })
        ->editColumn('game_date', function ($event) {
                if ( $event->full_weekend){
                  $end = Carbon::parse($event->game_date);
                  $end = $end->addDays(1);
                  return $event->game_date->format('D d.m.Y').' / '. $end->format('D d.m.Y');
                } else {
                  return $event->game_date->format('D d.m.Y');
                }
            })
        ->make(true);

      return view('schedule/scheduleevent_list', $data);
    }

    /**
     * Display a calendar listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list_cal()
    {
        Log::info('getting schedule events');
        // pass scheduled events back to calendar
        $data = ScheduleEvent::query()->get();
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Log::debug($request->input());

        // check if 2* or 3* size)
        $size = $request->input('schedule_size');
        $pos = strpos($size, '*');
        if ($pos === false ){
          $repeat = 1;
        } else {
          $tokSize = explode('*', $size);
          $size = $tokSize[1];
          $repeat = $tokSize[0];
        }

        $gamedays = ( ($size - 1) * 2 * $repeat);
        $startdate = CarbonImmutable::parse($request->input('startdate'));
        $startweekend = $startdate->endOfWeek(Carbon::SATURDAY);
        Log::info('need to create '.$gamedays.' events starting from '.$startdate.' / '.$startweekend);

        for ($i = 1; $i <= $gamedays; $i++){
          $gamedate = $startweekend;
      //    Log::debug($i.' ->  '.$startweekend.' -> '.$gamedate->addWeeks($i-1)->startOfDay());

          $ev = new ScheduleEvent;
          $ev->schedule_id = $request->input('schedule_id');
          $ev->game_day = $i;
          $ev->game_date = $gamedate->addWeeks($i-1)->startOfDay();
          $ev->full_weekend = true;
          $ev->save();

        }
        return redirect()->action('ScheduleEventController@list', ['id' => $request->input('schedule_id')]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function shift(Request $request)
    {
        Log::debug($request->input());

        if ( $request->input('direction') === '+'){
          ScheduleEvent::where('schedule_id', $request->input('schedule_id'))->update(['game_date' => DB::raw('DATE_ADD(game_date, INTERVAL '.$request->input('unitRange').' '.$request->input('unit').')')]);
        } else {
          ScheduleEvent::where('schedule_id', $request->input('schedule_id'))->update(['game_date' => DB::raw('DATE_SUB(game_date, INTERVAL '.$request->input('unitRange').' '.$request->input('unit').')')]);
        }

        return redirect()->action('ScheduleEventController@list', ['id' => $request->input('schedule_id')]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function show(Schedule $schedule)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function edit(Schedule $schedule)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Schedule $schedule)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function destroy(Schedule $schedule)
    {
      ///
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function list_destroy($id)
    {
      Log::info('deleting events for  '.print_r($id,true));
      ScheduleEvent::where('schedule_id', $id)->delete();
      return redirect()->action('ScheduleEventController@list', ['id' => $id]);
    }
}
