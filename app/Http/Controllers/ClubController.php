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
use Illuminate\Http\JsonResponse;
use Silber\Bouncer\BouncerFacade as Bouncer;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class ClubController extends Controller
{
    /**
     * Display a listing of the all resources.
     *
     * @param string $language
     * @param \App\Models\Region $region
     * @return \Illuminate\View\View
     *
     */
    public function index($language, Region $region)
    {
        Log::info('showing club list.');
        return view('club/club_list', ['region' => $region]);
    }

    /**
     * Display a listing of the resource .
     *
     * @return \Illuminate\Http\Response
     */
    public function list(Region $region)
    {
        if ($region->is_top_level) {
            Log::notice('getting clubs for top level region.');
            $clubs = Club::whereIn('region_id', $region->childRegions()->pluck('id'))->withCount([
                'leagues', 'teams', 'registered_teams', 'selected_teams', 'games_home',
                'games_home_notime', 'games_home_noshow'
            ])
                ->orderBy('shortname', 'ASC')
                ->get();
        } else {
            Log::notice('getting clubs for base level region.');
            $clubs = Club::where('region_id', $region->id)->withCount([
                'leagues', 'teams', 'registered_teams', 'selected_teams', 'games_home',
                'games_home_notime', 'games_home_noshow'
            ])
                ->orderBy('shortname', 'ASC')
                ->get();
        }
        // Log::debug(print_r($clubs,true));

        Log::info('preparing club list');
        $clublist = datatables()::of($clubs);

        return $clublist
            ->addIndexColumn()
            ->rawColumns(['shortname.display', 'name.display', 'assigned_rel.display', 'registered_rel.display', 'selected_rel.display'])
            ->editColumn('shortname', function ($data) {
                if ((Bouncer::can('access', $data)) or (Bouncer::is(Auth::user())->a('regionadmin'))) {
                    $link = '<a href="' . route('club.dashboard', ['language' => Auth::user()->locale, 'club' => $data->id]) . '">' . $data->shortname . '</a>';
                } else {
                    $link = '<a href="' . route('club.briefing', ['language' => Auth::user()->locale, 'club' => $data->id]) . '" class="text-info" >' . $data->shortname . '</a>';
                }
                return array('display' => $link, 'sort' => $data->shortname);
            })
            ->editColumn('region', function ($data) {
                return $data->region->code;
            })
            ->editColumn('name', function ($data) {
                $link = '<a href="http://' . $data->url . '" target="_blank">' . $data->name . '</a>';
                return array('display' => $link, 'sort' => Str::slug($data->name, '-'));
            })
            ->addColumn('assigned_rel', function ($data) {
                if ($data->teams_count != 0) {
                    $assigned_rel = round(($data->leagues_count * 100) / $data->teams_count);
                } else {
                    $assigned_rel = 0;
                }
                $content = '<div class="progress" style="height: 20px;">
            <div class="progress-bar bg-info" role="progressbar" style="width: ' . $assigned_rel . '%" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100">' . $assigned_rel . '%</div>
            </div>';
                return array('display' => $content, 'sort' => $assigned_rel);
            })
            ->addColumn('registered_rel', function ($c) {
                if ($c->teams_count != 0) {
                    $registered_rel = round(($c->registered_teams_count * 100) / $c->teams_count);
                } else {
                    $registered_rel = 0;
                }
                $content = '<div class="progress" style="height: 20px;">
          <div class="progress-bar" role="progressbar" style="width: ' . $registered_rel . '%" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100">' . $registered_rel . '%</div>
          </div>';
                return array('display' => $content, 'sort' => $registered_rel);
            })
            ->addColumn('selected_rel', function ($c) {
                if ($c->teams_count != 0) {
                    $selected_rel = round(($c->selected_teams_count * 100) / $c->teams_count);
                } else {
                    $selected_rel = 0;
                }
                $content = '<div class="progress" style="height: 20px;">
          <div class="progress-bar bg-success" role="progressbar" style="width: ' . $selected_rel . '%" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100">' . $selected_rel . '%</div>
          </div>';
                return array('display' => $content, 'sort' => $selected_rel);
            })
            ->editColumn('updated_at', function ($c) {
                return ($c->updated_at == null) ? null : $c->updated_at->format('d.m.Y H:i');
            })
            ->make(true);
    }

    /**
     * Display a dashboard
     *
     * @param Request $request
     * @param string $language
     * @param \App\Models\Club $club
     * @return \Illuminate\View\View
     *
     */
    public function dashboard(Request $request, $language, Club $club)
    {
        if  ( Bouncer::cannot('access', $club)) {
            Log::warning('[ACCESS DENIED]',['url'=> $request->path(), 'ip'=> $request->ip() ]);
            abort(403);
        }
        $data['club'] = $club;

        $data['gyms'] = $data['club']->gyms()->get();
        $data['teams'] = $data['club']->teams->count();
        $data['leagues'] = $data['club']->leagues->count();
        //$data['members'] = $data['club']->members()->get();
        $data['members'] = Member::whereIn('id', Club::find($club->id)->members()->pluck('member_id'))->with('memberships')->get();
        $data['games_home'] = $data['club']->games_home()->get();
        $data['registered_teams'] = $data['club']->registered_teams->pluck('league_id')->count();
        $data['selected_teams'] = $data['club']->selected_teams->pluck('league_id')->count();
        $data['games_home_notime'] = $data['club']->games_home_notime()->count();
        $data['games_home_noshow'] = $data['club']->games_home_noshow()->count();
        //Log::debug(print_r($data['games_home'],true ));

        $directory = $club->region->club_folder;
        $reports = collect(Storage::files($directory))->filter(function ($value) use ($club) {
            return Str::contains($value, $club->shortname);
        });

        //Log::debug(print_r($reports,true));
        $data['files'] = $reports;

        Log::info('showing club dashboard',['club-id'=>$club->id]);
        return view('club/club_dashboard', $data);
    }


    /**
     * club  teams datatable
     *
     * @param Request $request
     * @param string $language
     * @param \App\Models\Club $club
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function team_dt(Request $request, string $language, Club $club): JsonResponse
    {
        $clubteam = $club->teams;

        // get leagues where club is assigned
        $clubleagues = $club->leagues;
        //  remove all leagues where a team is alread registered
        foreach ($clubteam as $ct){
            $k = $clubleagues->search(function ($l) use ($ct) {
                return ($l['id'] == $ct['league_id']);
            });

            if ($k !== false){
                $clubleagues->pull($k);
            }
        }

        $teamlist = datatables()::of($clubteam);
        Log::info('preparing team list');
        return $teamlist
            ->addIndexColumn()
            ->rawColumns([
                'action','team','registered', 'selected','league.display','coach'
            ])
            ->addColumn('training', function ($ct) {
                return  Carbon::now()->startOfWeek($ct->training_day)->locale(app()->getLocale())->shortDayName .', '. Carbon::create($ct->training_time)->isoFormat('HH:mm');
            })
            ->addColumn('gameday', function ($ct) {
                if ($ct->preferred_game_day != null){
                    return Carbon::now()->startOfWeek($ct->preferred_game_day)->locale(app()->getLocale())->shortDayName .', '. Carbon::create($ct->preferred_game_time)->isoFormat('HH:mm');
                } else {
                    return '';
                }
            })
            ->addColumn('coach', function ($ct) {
                return '<a href="mailto:'.$ct->coach_email .'" >'.$ct->coach_name . '</a><i class="fas fa-phone m-2"></i>'. $ct->coach_phone1 ;
            })
            ->addColumn('action', function ($ct) use ($club) {
                if ( ! ( ($ct->league_id != null ) and ($ct->league->state->in([ LeagueState::Selection, LeagueState::Scheduling, LeagueState::Freeze, LeagueState::Live, LeagueState::Referees ])) )) {
                    $btn = '<span data-toggle="tooltip" title="'.__('team.action.delete',['name'=> $ct->name]).'">';
                    $btn .= '<button id="deleteTeam" data-team-id="'.$ct->id.'" data-league-sname="';
                    $btn .= isset($ct->league->shortname) ? $ct->league->shortname : __('team.unassigned');
                    $btn .= ' data-team-no="'.$ct->team_no .'" data-club-sname="'. $club->shortname .'" type="button"';
                    $btn .= ' class="btn btn-outline-danger btn-sm " ';
                    $btn .= Auth::user()->cannot('create-teams') ? ' disabled ' : '';
                    $btn .= '> <i class="fas fa-trash"></i></button></span>';
                }
                return $btn ?? '';
            })
            ->addColumn('registered', function ($ct) {
                if ($ct->league_id != null ){
                    return '<i class="far fa-check-circle text-success"></i>';
                } else {
                    return '';
                }
            })
            ->addColumn('selected', function ($ct) {
                if ($ct->league_no != null ){
                    return '<i class="far fa-check-circle text-success"></i>';
                } else {
                    return '';
                }
            })
            ->addColumn('team', function ($ct) {
                if (Auth::user()->can('update-teams')){
                    $item = '<span data-toggle="tooltip" title="'.__('team.action.edit',['name'=> $ct->name]).'">';
                    $item .= '<a href="'.route('team.edit', ['language' => app()->getLocale(), 'team' => $ct->id]).'">'.$ct->namedesc;
                    $item .= '<i class="fas fa-arrow-circle-right"></i></a></span>';
                } else {
                    $item = $ct->name;
                }
                return $item;
            })
            ->addColumn('league', function ($ct) use($clubleagues) {
                if ( $ct->league_id != null ){
                    if ( $ct->league->state->in([LeagueState::Registration, LeagueState::Selection ]) ){
                        $btn = '<span data-toggle="tooltip" title="'.__('team.tooltip.deassign',['name'=> $ct->name]).'">';
                        $btn .= '<button id="unregisterTeam" data-league-id="'.$ct->league->id.'" data-team-id="'.$ct->id.'" ';
                        $btn .= 'type="button" class="btn btn-secondary btn-sm">'.$ct->league['shortname'].'</button>';
                        $btn .= ( $ct->league_no != null) ? '<button type="button" class="btn btn-danger btn-sm pl-2">'.$ct->league_no.'</button>' : '';
                        $btn .= '</span>';
                    } else {
                        $btn = '<span><button type="button" class="btn btn-secondary btn-sm" disabled> '.$ct->league->shortname.'</button>';
                        $btn .= ( $ct->league_no != null ) ? ' <button type="button" class="btn btn-danger btn-sm pl-2" disabled >'.$ct->league_no .'</button>' : '';
                        $btn .= '</span>';
                    }
                } else {
                    if ( Auth::user()->can('update-teams')){
                        $btn = '<div class="btn-group"><button type="button" class="btn btn-secondary dropdpwn-toggle" data-toggle="dropdown">'.__('league.action.select').' ('.__('previous').': '. $ct->league_prev .')</button>';
                        $btn .= '<div class="dropdown-menu">';
                        foreach ($clubleagues as $cl){
                            $btn .= '<a class="dropdown-item" href="javascript:registerTeam('.$cl->id.','.$ct->id.') ">'.$cl->shortname.'</a>';
                        }
                        $btn .='</div></div>';
                    }
                }
                return array('display' => $btn ?? '', 'sort' => $ct->league->shortname ?? '');
            })
            ->make(true);
    }

    /**
     * Display a brief overview
     *
     * @param string $language
     * @param \App\Models\Club $club
     * @return \Illuminate\View\View
     *
     */
    public function briefing($language, Club $club)
    {
        $data['club'] = $club;

        $data['gyms'] = $data['club']->gyms()->get();
        $data['teams'] = $data['club']->teams()->with('league')->get()->sortBy('league.shortname');
        $data['memberships'] = $data['club']->memberships()->with('member')->get();

        Log::info('showing club briefing',['club-id'=>$club->id]);
        return view('club/club_briefing', $data);
    }


    /**
     * Display a listing of the resource for selectboxes.
     *
     * @param \App\Models\Region $region
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function sb_region(Region $region)
    {
        if ($region->is_top_level) {
            Log::notice('getting clubs for top level region');
            $clubs = Club::whereIn('region_id', $region->childRegions->pluck('id'))->orderBy('shortname', 'ASC')->get();
        } else {
            Log::notice('getting clubs for base level region');
            $clubs = $region->clubs()->orderBy('shortname', 'ASC')->get();
        }

        Log::info('preparing select2 club list.', ['count' => count($clubs)] );
        $response = array();

        foreach ($clubs as $club) {
            if ($club->region->is($region)) {
                $response[] = array(
                    "id" => $club->id,
                    "text" => $club->shortname
                );
            } else {
                $response[] = array(
                    "id" => $club->id,
                    "text" => '(' . $club->region->code . ') ' . $club->shortname
                );
            }
        }

        return Response::json($response);
    }

    /**
     * Display a listing of the resource for selectboxes. leagues for club
     *
     * @param \App\Models\Club $club
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function sb_league(Club $club)
    {
        //Log::debug(print_r($club,true));

        $leagues = $club->leagues()->orderBy('shortname', 'ASC')->get();

        Log::info('preparing select2 league list for a club', ['club-id'=> $club->id, 'count' => count($leagues)] );
        $response = array();

        foreach ($leagues as $league) {
            if ($league->state->is(LeagueState::Registration())) {
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
     * @param string $language
     * @param \App\Models\Region $region
     * @return \Illuminate\View\View
     *
     */
    public function create($language, Region $region)
    {
        Log::info('create new club');
        return view('club/club_new', ['region' => $region]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param \App\Models\Region $region
     * @return \Illuminate\Http\RedirectResponse
     *
     */
    public function store(Request $request, Region $region)
    {
        $data = $request->validate([
            'shortname' => array('required', 'string', 'unique:clubs', 'max:4', 'min:4', new Uppercase),
            'name' => 'required|max:255',
            'url' => 'required|url|max:255',
            'club_no' => 'required|unique:clubs|max:7',
        ]);
        Log::info('club form data validated OK.');

        $club = new Club($data);
        $region->clubs()->save($club);
        Log::notice('new club created.', ['club-id'=>$club->id]);

        return redirect()->route('club.index', ['language' => app()->getLocale(), 'region' => $region]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param string $language
     * @param  \App\Models\Club  $club
     * @return \Illuminate\View\View
     *
     */
    public function edit($language, Club $club)
    {
        Log::info('editing club.', ['club-id' => $club->id]);
        return view('club/club_edit', ['club' => $club]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param string $language
     * @param  \App\Models\Club  $club
     * @return \Illuminate\View\View
     *
     */
    public function list_homegame($language, Club $club)
    {
        Log::info('listing homegames for club ',['club-id'=> $club->id] );
        return view('game/gamehome_list', ['club' => $club]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Club  $club
     * @return \Illuminate\Http\RedirectResponse
     *
     */
    public function update(Request $request,  Club $club)
    {
        $data = $request->validate([
            'shortname' => array('required', 'string', Rule::unique('clubs')->ignore($club->id), 'max:4', 'min:4', new Uppercase),
            'name' => 'required|max:255',
            'url' => 'required|url|max:255',
            'club_no' => array('required', Rule::unique('clubs')->ignore($club->id), 'max:7'),
        ]);
        Log::info('club form data validated OK.');

        $check = $club->update($data);
        $club->refresh();
        Log::notice('club updated.', ['club-id'=> $club->id]);
        return redirect()->route('club.dashboard', ['language' => app()->getLocale(), 'club' => $club]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Club  $club
     * @return \Illuminate\Http\RedirectResponse
     *
     */

    public function destroy(Club $club)
    {

        // delete all dependent items
        $club->teams()->delete();
        Log::info('club teams deleted',['club-id'=>$club->id]);

        $club->gyms()->delete();
        Log::info('club gyms deleted',['club-id'=>$club->id]);

        $club->leagues()->detach();
        Log::info('club leagues detached',['club-id'=>$club->id]);

        $mships = $club->memberships()->get();
        foreach ($mships as $ms) {
            $ms->delete();
        }
        Log::info('club memberships deleted',['club-id'=>$club->id]);

        $region = $club->region;
        $club->delete();
        Log::notice('club deleted',['club-id'=>$club->id]);

        return redirect()->route('club.index', ['language' => app()->getLocale(), 'region' => $region]);
    }
}
