<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\Game;
use App\Imports\HomeGamesImport;

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

        Log::info('got home games for club.', ['club-id' => $club->id, 'count' => $ogames->count()]);
        $games = Game::query()->where('club_id_home', $club->id)->with('league', 'gym')->get();
        $glist = datatables()::of($games);

        $glist =  $glist
            ->rawColumns(['game_no.display', 'duplicate'])
            ->editColumn('game_time', function ($game) {
                return ($game->game_time == null) ? '' :  Carbon::parse($game->game_time)->isoFormat('LT');
            })
            ->editColumn('game_no', function ($game) {
                $link = '<a href="#" id="gameEditLink" data-id="' . $game->id .
                    '" data-game-date="' . $game->game_date . '" data-game-time="' . $game->game_time . '" data-club-id-home"' . $game->club_id_home .
                    '" data-gym-no="' . $game->gym_no . '" data-gym-id="' . $game->gym_id . '" data-league="' . $game->league['shortname'] .
                    '">' . $game->game_no . ' <i class="fas fa-arrow-circle-right"></i></a>';
                return array('display' => $link, 'sort' => $game->game_no);
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
                    'display' => $game->gym_no . ' - ' . $game['gym']['name'],
                    'default' => $game->gym_no
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
