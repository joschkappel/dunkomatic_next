<?php

namespace App\Http\Controllers;

use App\Models\LeagueSize;
use App\Models\Schedule;
use App\Models\Region;
use App\Models\League;

use Illuminate\Http\Request;

use Datatables;
use Bouncer;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Database\Eloquent\Builder;

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
    public function sb_region(Region $region)
    {
      if ($region->is_base_level){
        $schedules = Schedule::whereIn('region_id', [ $region->id, $region->parentRegion->id ] )->orderBy('name','ASC')->get();

      } else {
          $schedules = $region->schedules()->orderBy('name','ASC')->get();
      }

      Log::debug('got schedules '.count($schedules));
      $response = array();

      foreach($schedules as $schedule){
          if ($schedule->region->is( $region)){
            $response[] = array(
                    "id"=>$schedule->id,
                    "text"=>$schedule->name
                );
            } else {
                $response[] = array(
                    "id"=>$schedule->id,
                    "text"=>'(' . $schedule->region->code .') ' . $schedule->name
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
                          ->orderBy('name','ASC')->get();

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
    public function sb_size(Schedule $schedule)
    {

      // $schedules = session('cur_region')->schedules()
      $schedules = Schedule::where('id','!=',$schedule->id)
                             ->where('league_size_id', $schedule->league_size_id)
                             ->where('iterations', $schedule->iterations)
                             ->orderBy('name','ASC')
                             ->get();

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
    public function list(Region $region)
    {
//        $region = Region::find($user_region);
//        $hq_region = $region->hq;

//        if (isset($hq_region)){
//          $user_region = array( $user_region, $hq_region);
//        } else {
//        }

        $schedule = session('cur_region')->schedules()->with('league_size')->withCount('events')->get();

        $stlist = datatables()::of($schedule);

        return $stlist
          ->addIndexColumn()
          ->addColumn('action', function($data){
              if (! $data->leagues()->exists()){
                 $btn = '<button type="button" id="deleteSchedule" name="deleteSchedule" class="btn btn-outline-danger btn-sm" data-schedule-id="'.$data->id.'"
                    data-schedule-name="'.$data->name.'" data-events="'.count($data->events).'" data-toggle="modal" data-target="#modalDeleteSchedule"><i class="fa fa-trash"></i></button>';
                  return $btn;
              } else {
                return '';
              }
          })
          ->addColumn('color', function($data){
              return '<spawn style="background-color:'.$data->eventcolor.'">'.$data->eventcolor.'</div>';
          })
          ->addColumn('used_by_leagues', function($data){
            return $data->leagues->pluck('shortname')->implode(', ');
          })
          ->addColumn('events', function($data){
              if ($data->custom_events) {
                return __('Custom');
              } else {
                if ( (League::where('schedule_id',$data->id)->has('games')->count() == 0) and (Bouncer::can(['update-schedules'])) ) {
                  return '<a href="' . route('schedule_event.list', $data) .'">'.$data->events_count.' <i class="fas fa-arrow-circle-right"></i></a>';
                } else {
                  return $data->events_count;
                }
              }
          })
          ->rawColumns(['name','color','events','action'])
          ->editColumn('created_at', function ($user) {
                  return $user->created_at->format('d.m.Y H:i');
              })
          ->editColumn('name', function ($data) {
                if (Bouncer::canAny(['create-schedules', 'update-schedules'])){
                    return '<a href="' . route('schedule.edit', ['language'=>Auth::user()->locale, 'schedule' =>$data->id]) .'">'.$data->name.' <i class="fas fa-arrow-circle-right"></i></a>';
                } else {
                    return $data->name;
                }
              })
          ->editColumn('iterations', function ($data) {
            if ($data->iterations == 1){
              return __('schedule.single');
            } elseif ($data->iterations == 2){
              return __('schedule.double');
            } elseif ($data->iterations == 3){
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
    public function create($language)
    {
      Log::info('create new schedule');
      return view('schedule/schedule_new');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $data = $request->validate( Schedule::$createRules );

      if ($request['custom_events'] == 'on'){
        $data['custom_events'] = true;
        $data['league_size_id'] = LeagueSize::UNDEFINED;
      } else {
        $data['custom_events'] = false;
      }

      Log::debug(print_r($data, true));

      $check = Schedule::create($data);
      return redirect()->route('schedule.index', app()->getLocale());
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
        Log::info('editing schedule '.print_r($schedule->id,true));

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
      Log::info('validating '.$schedule->id);
      Log::debug(print_r($request->all(), true));

      $data = $request->validate( Schedule::$updateRules );

      if ($request['custom_events'] == 'on'){
        $data['custom_events'] = true;
        $data['league_size_id'] = LeagueSize::UNDEFINED;
      } else {
        $data['custom_events'] = false;
      }

      $schedule->update($data);
      return redirect()->route('schedule.index', app()->getLocale());
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

        return redirect()->route('schedule.index', app()->getLocale());
    }
}
