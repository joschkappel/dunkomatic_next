<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\Game;
use App\Imports\HomeGamesImport;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

use Datatables;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;


class ClubGameController extends Controller
{

    /**
     * Show the form for editing the specified resource.
     *
     * @param string $language
     * @param  \App\Models\Club  $club
     * @return \Illuminate\View\View
     *
     */
    public function show_games($language, Club $club)
    {
        $hgames = $club->games_home->pluck('game_date')->unique()->sort();
        Log::info('showing game selector for club ',['club-id'=> $club->id] );
        return view('game.club_game_list', ['club' => $club, 'game_dates' => $hgames]);
    }
    /**
     * chart.js with club games
     *
     * @param \App\Models\Club $club
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function chart_games(Club $club)
    {
        Log::info('collecting club game chart data.', ['club-id' => $club->id]);

        $game_slot = $club->region->game_slot;
        $min_slot = $game_slot - 1;

        $data = array();
        $datasets = array();

        $datasets[0]['label'] = __('game.guest');
        $datasets[0]['stack'] = 'Auswärts';
        $datasets[0]['data'] = [];
        $datasets[0]['backgroundColor'] = 'DeepSkyBlue';
        $datasets[1]['label'] = trans_choice('game.homegame',2);
        $datasets[1]['stack'] = 'Heim';
        $datasets[1]['data'] = [];
        $datasets[1]['backgroundColor'] = 'MediumSeaGreen';
        $datasets[2]['label'] = __('club.game_notime');
        $datasets[2]['stack'] = 'Heim';
        $datasets[2]['data'] = [];
        $datasets[2]['backgroundColor'] = 'Gold';
        $datasets[3]['label'] = __('club.game_overlap',['overlap'=>$game_slot]);
        $datasets[3]['stack'] = 'Heim';
        $datasets[3]['data'] = [];
        $datasets[3]['backgroundColor'] = 'Crimson';


        $select = 'SELECT ga.id
                FROM games ga
                JOIN games gb on ga.game_time <= date_add(gb.game_time, INTERVAL ' . $min_slot . ' minute)
                    and date_add(ga.game_time,interval ' . $min_slot . ' minute) >= gb.game_time
                    and ga.club_id_home=gb.club_id_home and ga.gym_no = gb.gym_no and ga.game_date = gb.game_date
                    and ga.id != gb.id
                WHERE ga.club_id_home=' . $club->id . ' ORDER BY ga.game_date DESC, ga.club_id_home ASC';

        $ogames = collect(DB::select($select))->pluck('id')->unique();

        $games = $club->games_home()->get();
        $games = $games->concat($club->games_guest()->get());
        $game_dates = $games->pluck('game_date')->unique()->sort();
        // transform from carno date to date string
        $game_dates->transform(function ($item, $key) {
            return $item->isoFormat('L');
        });
        $chart_date = array();
        foreach($game_dates as $g){
            $chart_date[] = $g;
        }
        $data['labels'] = $chart_date;
        //$data['labels'] = '1st label';

        $games = $games->groupBy('game_date')->sortKeys();
        foreach ($games as $g){
            $guest = $g->where('club_id_guest',$club->id)->count();
            $notime = $g->where('club_id_home',$club->id)->whereNull('game_time')->count();
            $overlap = $g->pluck('id')->intersect($ogames)->count();
            $remain_home = $g->where('club_id_home',$club->id)->whereNotNull('game_time')->count() - $overlap;
            $datasets[0]['data'][] = $guest;
            $datasets[1]['data'][] = $remain_home;
            $datasets[2]['data'][] = $notime;
            $datasets[3]['data'][] = $overlap;
        }

        $data['datasets'] = $datasets;

        return Response::json($data);
    }

    /**
     * databtales with club games
     *
     * @param \App\Models\Club $club
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function games_by_date_dt(string $language, Club $club, $game_date){
        $game_date = CarbonImmutable::createFromTimestamp($game_date);
        Log::info('searching games',['club'=>$club->id,'date'=>$game_date->toDateString()]);

        $games = Game::whereDate('game_date', $game_date->toDateString())
                ->where(function ($query) use($club) {
                    $query->where('club_id_home', $club->id)
                        ->orWhere('club_id_guest',$club->id);
                })
                ->get();

        Log::info('found some games for date',['club'=>$club->id,'count'=>$games->count()]);

        $games_bytime = $games->groupBy('game_time')->sortKeys();

        $game_slot = $club->region->game_slot;
        $min_slot = $game_slot - 1;

        $select = 'SELECT ga.id
                FROM games ga
                JOIN games gb on ga.game_time <= date_add(gb.game_time, INTERVAL ' . $min_slot . ' minute)
                    and date_add(ga.game_time,interval ' . $min_slot . ' minute) >= gb.game_time
                    and ga.club_id_home=gb.club_id_home and ga.gym_no = gb.gym_no and ga.game_date = gb.game_date
                    and ga.id != gb.id
                WHERE ga.club_id_home=' . $club->id . ' ORDER BY ga.game_date DESC, ga.club_id_home ASC';

        $ogames = collect(DB::select($select))->pluck('id')->unique();

        // now reformat to preapre the table rows and cols for datatables
        $gameslist = collect();
        foreach ($games_bytime as $key => $gtime){
            $new_row = array();
            $gym_row_list= collect();
            $new_row['game_time'] = $key == '' ? __('Undefined') : Carbon::parse($key)->IsoFormat('LT');
            $new_row['guest'] = '';
            $gym_row_list->push('guest');
            foreach($club->gyms as $g){
                $new_row['gym_'.$g->gym_no] = '';
                $gym_row_list->push('gym_'.$g->gym_no);
            }
            foreach ($gtime->values() as $g){
                $g->load('league');
                if ($g->club_id_home == $club->id){
                    $btndata = ' data-id="' . $g->id .
                        '" data-game-date="' . $g->game_date . '" data-game-time="' . $g->game_time . '" data-club-id-home"' . $g->club_id_home .
                        '" data-gym-no="' . $g->gym_no . '" data-gym-id="' . $g->gym_id . '" data-league="' . $g->league['shortname'] . '"';
                    if ($g->game_time == ''){
                        $new_row['gym_'.$g->gym_no] .= '<button id="gameEditLink" class="btn btn-warning btn-sm text-sm" '.$btndata.'>('.$g->league->shortname.') '.$g->team_home.' - '.$g->team_guest.'</button>';
                    } else {
                        if ($ogames->contains($g->id)){
                            $new_row['gym_'.$g->gym_no] .= '<button id="gameEditLink" class="btn btn-danger btn-sm text-sm" '.$btndata.'>('.$g->league->shortname.') '.$g->team_home.' - '.$g->team_guest.'</button>';
                        } else {
                            $new_row['gym_'.$g->gym_no] .= '<button id="gameEditLink" class="btn btn-success btn-sm text-sm" '.$btndata.'>('.$g->league->shortname.') '.$g->team_home.' - '.$g->team_guest.'</button>';
                        }
                    }
                } else {
                    $new_row['guest'] .= '<button class="btn btn-primary btn-sm text-sm" disabled>('.$g->league->shortname.') '.$g->team_home.' - '.$g->team_guest.'</button>';
                }
            }
            $gameslist->push($new_row);
        }

        $gamelist_dt = Datatables::collection($gameslist);
        return $gamelist_dt
            ->rawColumns($gym_row_list->toArray())
            ->setRowClass('text-sm')
            ->make(true);


    }

    /**
     * view with chart of home games per day and gym
     *
     * @param string $language
     * @param \App\Models\Club $club
     * @return \Illuminate\View\View
     *
     */
    public function chart(string $language, Club $club)
    {
        Log::info('showing club home game chart');
        return view('club/club_hgame_chart', ['club' => $club]);
    }

    /**
     * datatables.net with home games
     *
     * @param string $language
     * @param \App\Models\Club $club
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function list_home(string $language, Club $club)
    {

        // get games that overlapp (within 90 minutes)
        // $duplicates = DB::table('games')
        //                ->select(DB::raw('game_date' ,'gym_no', 'game_time'))
        //                ->where('club_id_home', $club->id)
        //                ->groupBy('game_date', 'gym_no', 'game_time')
        //                ->havingRaw('COUNT(*) > ?', [1])
        //                ->pluck('game_date');

        //get minimum game slot
        $game_slot = $club->region->game_slot;
        $min_slot = $game_slot - 1;

        $select = 'SELECT ga.id
                FROM games ga
                JOIN games gb on ga.game_time <= date_add(gb.game_time, INTERVAL ' . $min_slot . ' minute)
                    and date_add(ga.game_time,interval ' . $min_slot . ' minute) >= gb.game_time
                    and ga.club_id_home=gb.club_id_home and ga.gym_no = gb.gym_no and ga.game_date = gb.game_date
                    and ga.id != gb.id
                WHERE ga.club_id_home=' . $club->id . ' ORDER BY ga.game_date DESC, ga.club_id_home ASC';

        $ogames = collect(DB::select($select))->pluck('id')->unique();

        $games = $club->games_home()->with('league', 'gym')->get();
        $games = $games->concat($club->games_guest()->with('league', 'gym')->get());
        $games = $games->sortBy([['game_date','asc'],['gym_no','asc'],['game_time','asc']]);
        Log::info('got home games for club.', ['club-id' => $club->id, 'count' => $games->count()]);

        $glist = datatables()::of($games);

        $glist =  $glist
            ->rawColumns(['game_no.display', 'duplicate'])
            ->editColumn('game_time', function ($game) {
                return ($game->game_time == null) ? '' :  Carbon::parse($game->game_time)->isoFormat('LT');
            })
            ->editColumn('game_no', function ($game) use($club) {
                if ($game->club_id_home == $club->id ){
                    // this is a home game, can be edited
                    $link = '<a href="#" id="gameEditLink" data-id="' . $game->id .
                        '" data-game-date="' . $game->game_date . '" data-game-time="' . $game->game_time . '" data-club-id-home"' . $game->club_id_home .
                        '" data-gym-no="' . $game->gym_no . '" data-gym-id="' . $game->gym_id . '" data-league="' . $game->league['shortname'] .
                        '">' . $game->game_no . ' <i class="fas fa-arrow-circle-right"></i></a>';
                    return array('display' => $link, 'sort' => $game->game_no, 'filter'=>'Heim');
                } else {
                    return array('display' =>  $game->game_no, 'sort' => $game->game_no, 'filter'=>'Gast');
                }
            })
            ->addColumn('duplicate', function ($game) use ($ogames, $game_slot) {
                $warning = '';
                if ($ogames->contains($game->id)) {
                    //Log::info('found it in ');
                    $warning = '<div class="text-center"><spawn class="bg-danger px-2"> <i class="fa fa-exclamation-triangle"></i>' . $game_slot . '</spawn></div>';
                };
                return $warning;
            })
            ->editColumn('game_date', function ($game) use ($language) {
                return array(
                    'display' => Carbon::parse($game->game_date)->locale($language)->isoFormat('ddd L'),
                    'ts' => Carbon::parse($game->game_date)->timestamp,
                    'filter' => Carbon::parse($game->game_date)->locale($language)->isoFormat('L')
                );
            })
            ->editColumn('gym_no', function ($game) {
                return array(
                    'display' => ($game->gym_no ?? '?') . ' - ' . ($game['gym']['name']??'?'),
                    'default' => ($game->gym_no ?? '?' )
                );
            })
            ->make(true);
        //Log::debug(print_r($glist,true));
        return $glist;
    }

    /**
     * chart.js with home games
     *
     * @param \App\Models\Club $club
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function chart_home(Club $club)
    {
        $select = "select date_format(g.game_date, '%b-%d-%Y') AS 't', ";
        $select .= " time_format(g.game_time, '%H') AS 'ghour', time_format(g.game_time, '%i') AS 'gmin', g.gym_no AS 'gym', gy.name AS 'gymname' ";
        $select .= ' FROM games g, gyms gy ';
        $select .= ' WHERE g.gym_id=gy.id AND g.club_id_home=' . $club->id;
        $select .= " ORDER BY g.gym_no, date_format(g.game_date, '%b-%d-%Y') ASC, g.game_time ASC";

        // Log::debug($select);
        $hgames = collect(DB::select($select));
        Log::info('got home games for club.', ['club-id' => $club->id, 'count' => $hgames->count()]);

        $hg_by_gym = array();
        $cgym = '';

        foreach ($hgames as $hg) {
            if ($cgym != $hg->gym) {
                $cgym = $hg->gym;
                $hg_by_gym[$cgym]['label'] = $hg->gymname;
                $hg_by_gym[$cgym]['data'] = new Collection;
            }
            $hg->y = intval($hg->ghour) + ($hg->gmin = intval($hg->gmin) / 60);
            unset($hg->gmin);
            unset($hg->ghour);
            unset($hg->gym);
            $hg_by_gym[$cgym]['data']->push($hg);
        }

        //Log::debug(print_r($hg_by_gym, true));
        Log::info('preparing home games chart data for club.', ['club-id' => $club->id]);
        return Response::json($hg_by_gym);
    }

    /**
     * Show the form for uploading game files
     *
     * @param string $language
     * @param  \App\Models\Club  $club
     * @return \Illuminate\View\View
     *
     */
    public function upload(string $language, Club $club)
    {
        Log::info('preparing file upload form for club.', ['club-id' => $club->id]);

        $cardtitle =  __('club.title.gamehome.import', ['club' => $club->shortname]);
        $uploadroute = route('club.import.homegame', ['language' => app()->getLocale(), 'club' => $club]);
        $context = 'club';

        return view('game.game_file_upload', ['cardTitle' => $cardtitle, 'uploadRoute' => $uploadroute, 'context' => $context]);
    }

    /**
     * update games with file contents
     *
     * @param  \Illuminate\Http\Request  $request
     * @param string $language
     * @param  \App\Models\Club  $club
     * @return \Illuminate\Http\RedirectResponse
     *
     */
    public function import(Request $request, $language, Club $club)
    {
        $data = $request->validate([
            'gfile' => 'required'
        ]);
        Log::info('upload form data validated OK.');
        Log::info('processing file upload.', ['club-id' => $club->id, 'file' => $data['gfile']->getClientOriginalName()]);
        // Log::debug(print_r($request->all(),true));
        //$fname = $request->gfile->getClientOriginalName();
        //$fname = $club->shortname.'_homegames.'.$request->gfile->extension();

        try {
            // $hgImport->import($path, 'local', \Maatwebsite\Excel\Excel::XLSX);
            Log::info('validating import data.', ['club-id' => $club->id]);
            $hgImport = new HomeGamesImport();
            Excel::import( $hgImport, $request->gfile->store('temp'));
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = Arr::sortRecursive($e->failures());
            $ebag = array();
            $frow = 0;
            foreach ($failures as $failure) {
                if ($frow != $failure->row()) {
                    $ebag[] = '---';
                };
                $ebag[] = __('import.row') . ' "' . $failure->row() . '", ' . __('import.column') . ' "' . $failure->attribute() . '": ' . $hgImport->buildValidationMessage($failure->errors()[0], $failure->values(), $failure->attribute());
                $frow = $failure->row();
            }
            Log::warning('errors found in import data.', ['count' => count($failures)]);
            return redirect()->back()->withErrors($ebag);
        }

        return redirect()->back()->with(['status' => 'All data imported']);
        //return redirect()->route('club.list.homegame', ['language'=>$language, 'club' => $club]);
    }
}
