<?php

namespace App\Http\Controllers;

use App\Club;
use App\Gym;
use App\Member;

use Illuminate\Http\Request;
use App\Rules\Uppercase;
use Illuminate\Validation\Rule;

use Datatables;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Database\Eloquent\Builder;

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
    public function list_stats()
    {
      //
      $region = Auth::user()->region;
      Log::debug('get clubs for region '.$region);

      if ($region == ''){
        $clubs = Club::query();
      } else {
        $clubs = Club::query()->where('region', '=', $region);
      }

      $clubs = $clubs->withCount(['leagues','teams','games_home',
                                     'games_home_notime' => function (Builder $query) {
                                          $query->whereNull('game_time');},
                                     'games_home_noshow' => function (Builder $query) {
                                          $query->whereNull('team_id_guest');},
                          ])
                        ->get();

      //Log::debug(print_r($leagues,true));

      $clublist = datatables::of($clubs);

      return $clublist
        ->addIndexColumn()
        ->rawColumns(['shortname'])
        ->editColumn('shortname', function ($data) {
            return '<a href="' . route('club.dashboard', ['language'=>app()->getLocale(),'id'=>$data->id]) .'">'.$data->shortname.'</a>';
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
        if ( Auth::user()->regionadmin )  {
          return view('club/club_list');
        } else {

          $clublist = explode( ",", Auth::user()->club_ids);

          if (count($clublist)>1) {
            return redirect()->action(
                'ClubController@dashboard', ['language'=>app()->getLocale(),'id' => $clublist[0]]
            );
          } else {
            return back();
          }

        }
    }
    /**
     * Display a dashboard
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard( $language, $id )
    {
        //
          Log::debug('id is - '.$id);
          if ( $id ){
            $data['club'] =  Club::find(intval($id));

            if ($data['club']){
              $data['gyms'] = $data['club']->gyms()->get();
              $data['teams'] = $data['club']->teams()->with('league')->get();
              $data['member_roles'] = $data['club']->member_roles()->with('role','member')->get();
//              $data['members'] = Member::with('member_roles.unit')->where('member_roles.unit.id',$id)->get();
  //            Log::debug(print_r($data['members'],true));
              $data['games_home'] = $data['club']->games_home()->get();
              //Log::debug(print_r($data['games_home'],true ));
              return view('club/club_dashboard', $data);
            }
          }

          return view('welcome');

    }
    /**
     * Display a listing of the resource .
     *
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
        //
        $region = Auth::user()->region;
        Log::debug('get clubs for region '.$region);

        if ($region == ''){
          $clublist = datatables::of(Club::query());
        } else {
          $clublist = datatables::of(Club::query()->where('region', '=', $region));
        }

        return $clublist
          ->addIndexColumn()
          // ->addColumn('action', function($data){
          //        $editUrl = url('club/'.$data->id.'/list');
          //         //                 $btn = '<a href="'.$editUrl.'"</a><button type="button" class="btn btn-secondary btn-sm"><i class="fa fa-edit"></i> Edit</button>';
          //        $btn = ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$data->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteClub"><i class="fa fa-fw fa-trash"></i>'.__('club.action.delete').'</a>';
          //
          //         return $btn;
          // })
          ->rawColumns(['shortname'])
          ->editColumn('created_at', function ($user) {
                  return $user->created_at->format('d.m.Y H:i');
              })
          ->editColumn('shortname', function ($data) {
              return '<a href="' . route('club.dashboard', ['language'=>app()->getLocale(),'id'=>$data->id]) .'">'.$data->shortname.'</a>';
              })
          ->make(true);
    }

    /**
     * Display a listing of the resource for selectboxes.
     *
     * @return \Illuminate\Http\Response
     */
    public function list_select()
    {
      $user_region = array( Auth::user()->region );

      $clubs = Club::query()->whereIn('region', $user_region)->orderBy('name','ASC')->get();

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
      return view('club/club_new', ['region' => Auth::user()->region]);
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

        $check = Club::create($data);
        return redirect()->route('club.index', ['language' => app()->getLocale()]);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Club  $club
     * @return \Illuminate\Http\Response
     */
    public function show(Club $club)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Club  $club
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
     * @param  \App\Club  $club
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
     * @param  \App\Club  $club
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

        if(!$club->id){
           return redirect()->route('club.index', app()->getLocale() );
        }

        $check = club::where('id', $club->id)->update($data);
        return redirect()->route('club.dashboard',['language'=>app()->getLocale(), 'id'=>$club]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Club  $club
     * @return \Illuminate\Http\Response
     */

    public function destroy(Club $club)
    {
      Log::info(print_r($club->id, true));
      $check = Club::where('id', $club->id)->delete();

      return Response::json($check);
    }
}
