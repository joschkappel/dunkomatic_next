<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;

use BenSampo\Enum\Rules\EnumValue;
use App\Enums\JobFrequencyType;
use App\Enums\ReportFileType;

use App\Models\Region;
use App\Enums\Role;
use App\Models\Member;
use App\Notifications\CharPickingEnabled;
use Illuminate\Support\Facades\Auth;

use Carbon\Carbon;
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
      Log::info('listing regions');
      return view('admin.region_list');
    }

    /**
     * Display a dashboard
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard( $language, Region $region )
    {
          $data['region'] = Region::withCount('clubs','gyms','teams','leagues','childRegions')->find($region->id);
          $data['members'] = Member::whereIn('id', $region->members()->pluck('member_id'))->with('memberships')->get();

          return view('admin/region_dashboard', $data);

    }


    public function create()
    {
      Log::info('new region');
      return view('admin.region_new');
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
            'region_id' => 'sometimes|exists:regions,id',
            'name' => 'required',
            'code'  => 'required',
        ]);

        Log::info(print_r($data, true));
        if ( isset($data['region_id']) ){
            $data['hq'] = Region::find($data['region_id'])->code;
            unset($data['region_id']);
        }

        $check = Region::create($data);
        return redirect()->route('region.index', ['language' => app()->getLocale()]);

    }

    public function set_region(Region $region)
    {
      session(['cur_region' => $region]);

      return redirect()->route('home', app()->getLocale());
    }


    public function datatable($language)
    {
      Log::info('at least i ma here');
      $regions = Region::with('regionadmin')->withCount('clubs','leagues','teams','gyms')->get();
      Log::info('regions found:'.$regions->count());

      $regionlist = datatables()::of($regions);

      return $regionlist
        ->addIndexColumn()
        ->rawColumns(['regionadmin','code'])
        ->editColumn('code', function ($data) {
            return '<a href="' . route('region.dashboard', ['language'=>Auth::user()->locale,'region'=>$data->id]) .'">'.$data->code.'</a>';
            })        
        ->editColumn('regionadmin', function ($r) use ($language) {
            if ($r->regionadmin()->exists()){
                $admin = $r->regionadmin()->first()->firstname.' '.$r->regionadmin()->first()->lastname;
            } else {
                $admin = '<a href="'. route('membership.region.create', ['language'=>Auth::user()->locale,'region'=>$r->id]) .'"><i class="fas fa-plus-circle"></i></a>';
            }
            return $admin;
        })
        ->make(true);
    }

    public function admin_sb()
    {
      $regions = Region::query()->get();

      Log::debug('got regions '.count($regions));
      $response = array();

      foreach($regions as $region){
          Log::debug(print_r($region,true));
          if ( $region->regionadmin()->exists() ) {
            $response[] = array(
                  "id"=>$region->id,
                  "text"=>$region->name
                );
          }
      }
      Log::debug(print_r($response,true));

      return Response::json($response);
    }

    public function hq_sb()
    {
      $regions = Region::whereNull('hq')->get();

      Log::debug('got regions '.count($regions));
      $response = array();

      foreach($regions as $region){
        Log::debug(print_r($region,true));
        $response[] = array(
                "id"=>$region->id,
                "text"=>$region->name
           );
      }
      Log::debug(print_r($response,true));

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
        Log::info('Editing region'.$region->code);
        return view('region/region_edit', ['region'=>$region, 'frequencytype' => JobFrequencyType::getInstances(), 'filetype' => ReportFileType::getInstances()] );
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
        Log::debug(print_r($request->all(),true));
        $old_charpick = $region->pickchar_enabled;

        $data = $request->validate( [
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
            'pickchar_enabled' => 'sometimes|required|in:on'
        ]);

        Log::debug(print_r($data,true));
        if ( isset($data['pickchar_enabled']) and ( $data['pickchar_enabled'] === 'on' )){
          $data['pickchar_enabled'] = True;
        } else {
          $data['pickchar_enabled'] = False;
        }

        $check = Region::find($region->id)->update($data);

        // send out notifications
        if ($old_charpick != $data['pickchar_enabled']){
            $clubs = $region->clubs()->get();
            foreach ($clubs as $c){
                if ($c->memberIsA(Role::ClubLead)){
                    $clead = $c->members()->wherePivot('role_id', Role::ClubLead)->first();
                    $clead->notify(new CharPickingEnabled($c, $data['pickchar_enabled'], config('global.season')));
                }
            }
        }


        return redirect()->route('home',['language'=>app()->getLocale()]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Region $region
     * @return \Illuminate\Http\Response
     */
    public function destroy(Region $region)
    {
        Log::debug('about to delete region '.$region->name);

        $region->users()->delete();
        $region->schedules()->delete();
        // $region->messages()->delete();
        $region->members()->delete();
        $region->memberships()->delete();
        $region->delete();

        return redirect()->route('region.index', app()->getLocale());
    }

}
