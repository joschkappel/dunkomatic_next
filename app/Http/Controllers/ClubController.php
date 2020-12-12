<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\Region;
use App\Models\Gym;
use App\Models\Member;

use Illuminate\Http\Request;
use App\Rules\Uppercase;
use Illuminate\Validation\Rule;

use Datatables;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ClubController extends Controller
{

    public function index_stats()
    {
      return view('club/club_stats');
    }
    /**
     * Display a listing of the resource .
     *
     * @return \Illuminate\Http\Response
     */
    public function list_stats(Region $region)
    {

      $clubs = $region->clubs()->withCount(['leagues','teams','games_home',
                                     'games_home_notime','games_home_noshow'
                          ])
                        ->orderBy('shortname','ASC')
                        ->get();

      //Log::debug(print_r($leagues,true));

      $clublist = datatables::of($clubs);

      return $clublist
        ->addIndexColumn()
        ->rawColumns(['shortname'])
        ->editColumn('shortname', function ($data) {
            return '<a href="' . route('club.dashboard', ['language'=>app()->getLocale(),'club'=>$data->id]) .'">'.$data->shortname.'</a>';
            })
        ->make(true);

    }
    /**
     * Display a listing of the all resources.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('club/club_list');
    }
    /**
     * Display a dashboard
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard( $language, Club $club )
    {
          $data['club'] =  Club::find($club->id);
          $club =   $data['club'];

          $data['gyms'] = $data['club']->gyms()->get();
          $data['teams'] = $data['club']->teams()->with('league')->get();
          //$data['members'] = $data['club']->members()->get();
          $data['members'] = Member::whereIn('id',Club::find($club->id)->members()->pluck('member_id'))->with('memberships')->get();
          $data['games_home'] = $data['club']->games_home()->get();
          //Log::debug(print_r($data['games_home'],true ));

          $directory = Auth::user()->region->club_folder;
          $reports = collect(Storage::allFiles($directory))->filter(function ($value, $key) use ($club){
            return (strpos($value,$club->shortname) !== false);
          });

          Log::debug(print_r($reports,true));
          $data['files'] = $reports;
          return view('club/club_dashboard', $data);

    }
    /**
     * Display a listing of the resource .
     *
     * @return \Illuminate\Http\Response
     */
    public function list(Region $region)
    {
        $clublist = datatables::of($region->clubs);

        return $clublist
          ->addIndexColumn()
          // ->addColumn('action', function($data){
          //        $editUrl = url('club/'.$data->id.'/list');
          //         //                 $btn = '<a href="'.$editUrl.'"</a><button type="button" class="btn btn-secondary btn-sm"><i class="fa fa-edit"></i> Edit</button>';
          //        $btn = ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$data->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteClub"><i class="fa fa-fw fa-trash"></i>'.__('club.action.delete').'</a>';
          //
          //         return $btn;
          // })
          ->rawColumns(['shortname','url'])
          ->editColumn('created_at', function ($user) {
                  return $user->created_at->format('d.m.Y H:i');
              })
          ->editColumn('shortname', function ($data) {
              return '<a href="' . route('club.dashboard', ['language'=>app()->getLocale(),'club'=>$data->id]) .'">'.$data->shortname.'</a>';
              })
          ->editColumn('url', function ($data) {
              return '<a href="http://' . $data->url .'" target="_blank">'.$data->url.'</a>';
              })
          ->make(true);
    }

    /**
     * Display a listing of the resource for selectboxes.
     *
     * @return \Illuminate\Http\Response
     */
    public function sb_region(Region $region)
    {

      $clubs = $region->clubs()->orderBy('shortname','ASC')->get();

      Log::debug('got clubs '.count($clubs));
      $response = array();

      foreach($clubs as $club){
          $response[] = array(
                "id"=>$club->id,
                "text"=>$club->shortname
              );
      }

      return Response::json($response);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      Log::info('create new club');
      return view('club/club_new', ['region' => session('cur_region')]);
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
            'shortname' => array(
              'required',
              'string',
              'unique:clubs',
              'max:4',
              'min:4',
              new Uppercase ),
            'name' => 'required|max:255',
            'url' => 'required|url|max:255',
            'region' => 'required|max:5|exists:regions,code',
            'club_no' => 'required|unique:clubs|max:7',
        ]);

        Log::info(print_r($data, true));
        $data['region_id'] = Region::where('code',$data['region'])->first()->id;
        unset($data['region']);

        $check = Club::create($data);
        return redirect()->route('club.index', ['language' => app()->getLocale()]);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Club  $club
     * @return \Illuminate\Http\Response
     */
    public function show(Club $club)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Club  $club
     * @return \Illuminate\Http\Response
     */
    public function edit($language, Club $club)
    {
        Log::debug('editing club '.$club->id);
        return view('club/club_edit', ['club' => $club]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Club  $club
     * @return \Illuminate\Http\Response
     */
    public function list_homegame($language, Club $club)
    {
        Log::debug('listing hgames for club '.$club->id);
        return view('game/gamehome_list', ['club' => $club]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Club  $club
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,  Club $club)
    {

      $data = $request->validate( [
          'shortname' => array(
            'required',
            'string',
            Rule::unique('clubs')->ignore($club->id),
            'max:4',
            'min:4',
            new Uppercase ),
          'name' => 'required|max:255',
          'url' => 'required|url|max:255',
          'region' => 'required|max:5|exists:regions,code',
          'club_no' => array(
            'required',
             Rule::unique('clubs')->ignore($club->id),
             'max:7'),
      ]);

        $data['region_id'] = Region::where('code',$data['region'])->first()->id;
        unset($data['region']);
        if(!$club->id){
           return redirect()->route('club.index', app()->getLocale() );
        }

        $check = Club::find($club->id)->update($data);
        return redirect()->route('club.dashboard',['language'=>app()->getLocale(), 'club'=>$club]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Club  $club
     * @return \Illuminate\Http\Response
     */

    public function destroy(Club $club)
    {
      Log::info(print_r($club->id, true));
      $check = $club->delete();

      return Response::json($check);
    }
}
