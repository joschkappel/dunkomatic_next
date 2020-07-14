<?php

namespace App\Http\Controllers;

use App\Schedule;
use App\Region;
use App\LeagueTeamSize;


use Illuminate\Http\Request;
use Datatables;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Response;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      return view('schedule/schedule_list');
    }

    /**
     * Display a listing of the resource for selecbboxes
     *
     * @return \Illuminate\Http\Response
     */
    public function list_select()
    {
      $user_region = array( Auth::user()->region );


      $schedules = Schedule::query()->whereIn('region_id', $user_region)->orderBy('name','ASC')->get();

      Log::debug('got schedules '.count($schedules));
      $response = array();

      foreach($schedules as $schedule){
          $response[] = array(
                "id"=>$schedule->id,
                "text"=>$schedule->name
              );
      }

      return Response::json($response);
    }

    /**
     * Display a listing of the resource for selecbboxes
     *
     * @return \Illuminate\Http\Response
     */
    public function list_size_select(LeagueTeamSize $size)
    {
      $user_region = array( Auth::user()->region );
      Log::debug('SIZE :'.print_r($size,true));

      $schedules = Schedule::has('events')->whereIn('region_id', $user_region)
        ->where('size','=',$size->size)->orderBy('name','ASC')->get();

      Log::debug('got schedules '.count($schedules));
      $response = array();

      foreach($schedules as $schedule){
        $response[] = array(
              "id"=>$schedule->id,
              "text"=>$schedule->name
            );
      }

      return Response::json($response);
    }
    /**
     * Display a listing of the resource .
     *
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
        $user_region = Auth::user()->region;
//        $region = Region::find($user_region);
//        $hq_region = $region->hq;

//        if (isset($hq_region)){
//          $user_region = array( $user_region, $hq_region);
//        } else {
          $user_region = array( $user_region );
//        }

        $schedule = Schedule::query()->whereIn('region_id', $user_region)->with('size')->withCount('events')->get();

        $stlist = datatables::of($schedule);

        return $stlist
          ->addIndexColumn()
          ->addColumn('color', function($data){
              return '<spawn style="background-color:'.$data->eventcolor.'">'.$data->eventcolor.'</div>';
          })
          ->addColumn('events', function($data){
              return '<a href="' . route('schedule_event.list', $data) .'">'.$data->events_count.' <i class="fas fa-arrow-circle-right"></i></a>';
          })
          ->rawColumns(['name','color','events'])
          ->editColumn('created_at', function ($user) {
                  return $user->created_at->format('d.m.Y H:i');
              })
          ->editColumn('name', function ($data) {
              return '<a href="' . route('schedule.edit', $data->id) .'">'.$data->name.' <i class="fas fa-arrow-circle-right"></i></a>';
              })
          ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      Log::info('create new schedule');
      return view('schedule/schedule_new', ['region' => Auth::user()->region]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $data = $request->validate( [
          'name' => 'required',
          'region_id' => 'required|exists:regions,id',
          'eventcolor' => 'required',
          'active' => 'boolean',
          'size' => 'required|exists:league_team_sizes,size'
      ]);

      Log::debug(print_r($data, true));

      $check = Schedule::create($data);
      return redirect()->action(
          'ScheduleController@index')->withSuccess('New Schedule saved  ');
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
        Log::info('editing schedule '.print_r($schedule->id,true));

        return view('schedule/schedule_edit', ['schedule' => $schedule]);

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
      Log::info('validating '.$schedule->id);

      $data = $request->validate( [
          'name' => 'required',
          'region_id' => 'required',
          'eventcolor' => 'required',
          'active' => 'boolean'
      ]);

      $check = schedule::where('id', $schedule->id)->update($data);
      return redirect()->route('schedule.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Schedule $schedule
     * @return \Illuminate\Http\Response
     */
    public function destroy(Schedule $schedule)
    {
        Log::debug('about to delete scedule type '.$schedule->name);

        $schedule = Schedule::find($schedule->id);

        $schedule->events()->delete();
        $schedule->delete();

        Session::flash('flash_message', 'Schedule deleted');
        Session::flash('flash_type', 'alert-success');
        return redirect()->route('schedule.index')->with('message', 'Schedule deleted.');
    }
}
