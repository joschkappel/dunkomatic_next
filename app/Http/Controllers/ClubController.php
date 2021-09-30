<?php

namespace App\Http\Controllers;

use App\Enums\LeagueState;
use App\Models\Club;
use App\Models\Region;
use App\Models\Member;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Rules\Uppercase;

use Datatables;
use Bouncer;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ClubController extends Controller
{

    /**
     * Display a listing of the resource .
     *
     * @return \Illuminate\Http\Response
     */
    public function list(Region $region)
    {
      if ( $region->is_top_level){
        $clubs = Club::whereIn('region_id', $region->childRegions()->pluck('id') )->withCount(['leagues','teams','registered_teams','selected_teams','games_home',
                                        'games_home_notime','games_home_noshow'
                            ])
                            ->orderBy('shortname','ASC')
                            ->get();
      } else {
        $clubs = Club::where('region_id', $region->id)->withCount(['leagues','teams','registered_teams','selected_teams','games_home',
                                        'games_home_notime','games_home_noshow'
                            ])
                            ->orderBy('shortname','ASC')
                            ->get();
      }
      // Log::debug(print_r($clubs,true));

      $clublist = datatables()::of($clubs);

      return $clublist
        ->addIndexColumn()
        ->rawColumns(['shortname.display','name.display', 'assigned_rel.display', 'registered_rel.display', 'selected_rel.display'])
        ->editColumn('shortname', function ($data) {
            $link = '<a href="' . route('club.dashboard', ['language'=>Auth::user()->locale,'club'=>$data->id]) .'">'.$data->shortname.'</a>';
            return array('display' =>$link, 'sort'=>$data->shortname);
        })
        ->editColumn('region', function ($data) {
            return $data->region->code;
        })
        ->editColumn('name', function ($data){
          $link = '<a href="http://' . $data->url .'" target="_blank">'.$data->name.'</a>';
          return array('display' =>$link, 'sort'=>Str::slug($data->name,'-'));
        })
        ->addColumn('assigned_rel', function($data){
            if ($data->teams_count!=0){
                $assigned_rel = round(($data->leagues_count * 100)/$data->teams_count);
            } else {
                $assigned_rel = 0;
            }
            $content = '<div class="progress" style="height: 20px;">
            <div class="progress-bar bg-info" role="progressbar" style="width: '.$assigned_rel.'%" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100">'.$assigned_rel.'%</div>
            </div>';
            return array('display' =>$content, 'sort'=>$assigned_rel);
        })
        ->addColumn('registered_rel', function($c){
          if ($c->teams_count!=0){
              $registered_rel = round(($c->registered_teams_count * 100)/$c->teams_count);
          } else {
              $registered_rel = 0;
          }
          $content = '<div class="progress" style="height: 20px;">
          <div class="progress-bar" role="progressbar" style="width: '.$registered_rel.'%" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100">'.$registered_rel.'%</div>
          </div>';
          return array('display' =>$content, 'sort'=>$registered_rel);
        })
        ->addColumn('selected_rel', function($c){
          if ($c->teams_count!=0){
              $selected_rel = round(($c->selected_teams_count * 100)/$c->teams_count);
          } else {
              $selected_rel = 0;
          }
          $content = '<div class="progress" style="height: 20px;">
          <div class="progress-bar bg-success" role="progressbar" style="width: '.$selected_rel.'%" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100">'.$selected_rel.'%</div>
          </div>';
          return array('display' =>$content, 'sort'=>$selected_rel);
        })
        ->editColumn('updated_at', function ($c) {
          return ($c->updated_at==null) ? null : $c->updated_at->format('d.m.Y H:i');
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
          $data['club'] = $club;

          $data['gyms'] = $data['club']->gyms()->get();
          $data['teams'] = $data['club']->teams()->with('league')->get()->sortBy('league.shortname');
          $data['leagues'] = $data['club']->leagues()->get()->sortBy('shortname');
          //$data['members'] = $data['club']->members()->get();
          $data['members'] = Member::whereIn('id',Club::find($club->id)->members()->pluck('member_id'))->with('memberships')->get();
          $data['games_home'] = $data['club']->games_home()->get();
          $data['registered_teams'] = $data['club']->registered_teams->pluck('league_id');
          $data['selected_teams'] = $data['club']->selected_teams->pluck('league_id');
          $data['games_home_notime'] = $data['club']->games_home_notime()->count();
          $data['games_home_noshow'] = $data['club']->games_home_noshow()->count();
          //Log::debug(print_r($data['games_home'],true ));

          $directory = Auth::user()->region->club_folder;
          $reports = collect(Storage::allFiles($directory))->filter(function ($value, $key) use ($club){
            return (strpos($value,$club->shortname) !== false);
          });

          //Log::debug(print_r($reports,true));
          $data['files'] = $reports;
          return view('club/club_dashboard', $data);

    }

    /**
     * Display a listing of the resource for selectboxes.
     *
     * @return \Illuminate\Http\Response
     */
    public function sb_region(Region $region)
    {
        if ( $region->is_top_level ){
            $clubs = Club::whereIn('region_id', $region->childRegions->pluck('id'))->orderBy('shortname','ASC')->get();
        } else {
            $clubs = $region->clubs()->orderBy('shortname','ASC')->get();
        }

        Log::debug('got clubs '.count($clubs));
        $response = array();

        foreach($clubs as $club){
            if ($club->region->is($region)){
                $response[] = array(
                    "id"=>$club->id,
                    "text"=>$club->shortname
                );
            } else {
                $response[] = array(
                    "id"=>$club->id,
                    "text"=>'('.$club->region->code.') '.$club->shortname
                );
            }

        }

        return Response::json($response);
    }

  /**
   * Display a listing of the resource for selectboxes. leagues for club
   *
   * @return \Illuminate\Http\Response
   */
  public function sb_league(Club $club)
  {
    //Log::debug(print_r($club,true));

    $leagues = $club->leagues()->orderBy('shortname', 'ASC')->get();

    Log::debug('got leagues ' . count($leagues));
    $response = array();

    foreach ($leagues as $league) {
      if ($league->state->is( LeagueState::Assignment()) ){
        $response[] = array(
          "id" => $league->id,
          "text" => $league->shortname
        );
      }
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
        $data = $request->validate( Club::getCreateRules());

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
          'shortname' => array('required','string', Rule::unique('clubs')->ignore($club->id),'max:4','min:4',new Uppercase ),
          'name' => 'required|max:255',
          'url' => 'required|url|max:255',
          'region' => 'required|max:5|exists:regions,code',
          'club_no' => array('required',Rule::unique('clubs')->ignore($club->id),'max:7'),
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
      // delete all dependent items

      $club->teams()->delete();
      $club->gyms()->delete();
      $club->leagues()->detach();
      $mships = $club->memberships()->get();
      foreach ($mships as $ms){
          $ms->delete();
      }

      $club->delete();

      return redirect()->route('club.index', ['language'=> app()->getLocale()]);
    }
}
