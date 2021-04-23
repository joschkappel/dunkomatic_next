<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;

use BenSampo\Enum\Rules\EnumValue;
use App\Enums\JobFrequencyType;
use App\Enums\ReportFileType;

use App\Models\Region;
use App\Models\User;

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

    public function set_region(Region $region)
    {
      session(['cur_region' => $region]);

      return redirect()->route('home', app()->getLocale());
    }


    public function datatable($language)
    {
      Log::info('at least i ma here');
      $regions = Region::all();
      Log::info('regions found:'.$regions->count());

      $regionlist = datatables()::of($regions);

      return $regionlist
        ->addIndexColumn()
        ->editColumn('created_at', function ($r) use ($language) {
                return Carbon::parse($r->created_at)->locale($language)->isoFormat('lll');
            })
        ->editColumn('updated_at', function ($r) use ($language) {
                return Carbon::parse($r->updated_at)->locale($language)->isoFormat('lll');
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
    public function update(Request $request, Region $region)
    {
        Log::debug(print_r($request->all(),true));
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
        return redirect()->route('home',['language'=>app()->getLocale()]);
    }


}
