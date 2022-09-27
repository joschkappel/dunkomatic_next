<?php

namespace App\Http\Controllers;

use App\Imports\LeagueCustomGamesImport;
use App\Imports\LeagueGamesImport;
use App\Models\Game;
use App\Models\Gym;
use App\Models\League;
use App\Models\Team;
use App\Traits\GameManager;
use App\Traits\LeagueFSM;
use Carbon\Carbon;
use Datatables;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class LeagueGameController extends Controller
{
    use LeagueFSM, GameManager;

    /**
     * Display a listing of the resource.
     *
     * @param  string  $language
     * @param  \App\Models\League  $league
     * @return \Illuminate\View\View
     */
    public function index($language, League $league)
    {
        Log::info('showing league game list.', ['league-id' => $league->id]);

        return view('game/league_game_list', ['league' => $league]);
    }

    /**
     * Get a game by game number
     *
     * @param  \App\Models\League  $league
     * @param  int  $game_no
     * @return \Illuminate\Http\JsonResponse
     */
    public function show_by_number(League $league, $game_no)
    {
        Log::info('getting a game.', ['league-id' => $league->id, 'game-no' => $game_no]);
        $game = $league->games->where('game_no', $game_no)->first();

        return Response::json($game, 200);
    }

    /**
     * datatables.net list with a ll games for a league
     *
     * @param  string  $language
     * @param  \App\Models\League  $league
     * @return \Illuminate\Http\JsonResponse
     */
    public function datatable(string $language, League $league)
    {
        Log::info('preparing game list', ['league-id' => $league->id]);

        $games = $league->games()->with(['league.region', 'gym', 'team_home.club', 'team_guest.club'])->get();
        $glist = datatables()::of($games);

        $glist = $glist
            ->rawColumns(['game_no.display'])
            ->editColumn('game_time', function ($game) {
                return ($game->game_time == null) ? '' : Carbon::parse($game->game_time)->isoFormat('LT');
            })
            ->editColumn('game_no', function ($game) {
                $link = '<a href="#" id="gameEditLink" data-id="'.$game->id.
                    '" data-game-date="'.$game->game_date.'" data-game-time="'.$game->game_time.'" data-club-id-home="'.$game->club_id_home.
                    '" data-gym-no="'.$game->gym_no.'" data-gym-id="'.$game->gym_id.'" data-league="'.$game->league.
                    '" data-team-home="'.$game->team_home.'" data-team-id-home="'.$game->team_id_home.'" data-team-guest="'.$game->team_guest.'" data-team-id-guest="'.$game->team_id_guest.
                    '" data-game-no="'.$game->game_no.'" data-league-id="'.$game->league_id.
                    '">'.$game->game_no.' <i class="fas fa-arrow-circle-right"></i></a>';
                // $link = $game->game_no;
                return ['display' => $link, 'sort' => $game->game_no];
            })
            ->editColumn('game_date', function ($game) use ($language) {
                return [
                    'display' => Carbon::parse($game->game_date)->locale($language)->isoFormat('ddd L'),
                    'ts' => Carbon::parse($game->game_date)->timestamp,
                    'filter' => Carbon::parse($game->game_date)->locale($language)->isoFormat('L'),
                ];
            })
            ->editColumn('gym_no', function ($game) {
                return [
                    'display' => ($game->gym_no ?? '').' - '.($game['gym']->name ?? ''),
                    'default' => $game->gym_no ?? '',
                ];
            })
            ->addColumn('team_home', function ($game) {
                return $game->team_home;
            })
            ->addColumn('team_guest', function ($game) {
                return $game->team_guest;
            })
            ->make(true);
        //Log::debug(print_r($glist,true));
        return $glist;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Models\League  $league
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(League $league)
    {
        Log::info('creating games.', ['league-id' => $league->id]);
        $this->open_game_scheduling($league);

        return Response::json(['success' => 'all good'], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Game  $game
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Game $game)
    {
        if ($game->load('league.schedule')->league()->first()->schedule->custom_events) {
            Log::info('update game - custom schedule.', ['game-id' => $game->id]);
            $maxgames = $game->league()->first()->size * ($game->league()->first()->size - 1);

            $data = Validator::make($request->all(), [
                'gym_id' => 'required|exists:gyms,id',
                'game_date' => 'required|date|after:today',
                'game_time' => 'required|date_format:H:i',
                'team_id_home' => 'exists:teams,id|different:team_id_guest',
                'team_id_guest' => 'exists:teams,id|different:team_id_home',
                //     'game_no' => 'integer|between:1,12',
                'game_no' => [Rule::unique('games')->where(function ($query) use ($game) {
                    return $query->where('league_id', $game->league_id)->where('game_no', '!=', $game->game_no);
                }), 'integer', 'between:1,'.$maxgames],
            ])->validate();
            Log::info('game form data validate OK.', ['game-id' => $game->id]);

            // handle new home team
            if ($data['team_id_home'] != $game->team_id_home) {
                $team_home = Team::find($data['team_id_home']);
                $data['club_id_home'] = $team_home->club_id;
            } else {
                unset($data['team_id_home']);
            }

            // handle new guest team
            if ($data['team_id_guest'] != $game->team_id_guest) {
                $team_guest = Team::find($data['team_id_guest']);
                $data['club_id_guest'] = $team_guest->club_id;
            } else {
                unset($data['team_id_guest']);
            }
        } else {
            $data = Validator::make($request->all(), [
                'gym_id' => 'required|exists:gyms,id',
                'game_date' => 'required|date|after:today',
                'game_time' => 'required|date_format:H:i',
            ])->validate();
            Log::info('game form data validate OK.', ['game-id' => $game->id]);
        }

        $data['game_time'] = Carbon::parse($data['game_time'])->format('H:i');

        $game->update($data);
        Log::notice('game updated.', ['game-id' => $game->id]);

        return response()->json(['success' => 'Data is successfully added']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Game  $game
     * @return \Illuminate\Http\JsonResponse
     */
    public function update_home(Request $request, Game $game)
    {
        $data = Validator::make($request->all(), [
            'gym_id' => 'required|exists:gyms,id',
            'game_date' => 'required|date|after:today',
            'game_time' => 'required|date_format:H:i',
        ])->validate();
        Log::info('home game form data validate OK.', ['game-id' => $game->id]);

        $data['game_time'] = Carbon::parse($data['game_time'])->format('H:i');

        // Get GYM NO
        $data['gym_no'] = Gym::find($data['gym_id'])->gym_no;
        //Log::debug(print_r($game,true));
        $game->update($data);
        Log::notice('game updated.', ['game-id' => $game->id]);

        return response()->json(['success' => 'Data is successfully added']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\League  $league
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy_game(League $league)
    {
        $this->refreeze_league($league);
        $league->refresh();

        return Response::json(['success' => 'all good'], 200);
    }

    /**
     * Show the form for uploading game files
     *
     * @param  string  $language
     * @param  \App\Models\League  $league
     * @return \Illuminate\View\View
     */
    public function upload($language, League $league)
    {
        Log::info('preparing file upload form for league.', ['league-id' => $league->id]);

        $cardtitle = __('league.title.game.import', ['league' => $league->shortname]);
        $uploadroute = route('league.import.game', ['language' => app()->getLocale(), 'league' => $league]);

        return view('game.game_file_upload', ['cardTitle' => $cardtitle, 'uploadRoute' => $uploadroute, 'context' => 'league']);
    }

    /**
     * update imported games with file contents
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $language
     * @param  \App\Models\League  $league
     * @return \Illuminate\Http\RedirectResponse
     */
    public function import(Request $request, $language, League $league)
    {
        $data = $request->validate([
            'gfile' => 'required',
        ]);
        Log::info('upload form data validated OK.');
        Log::info('processing file upload.', ['league-id' => $league->id, 'file' => $data['gfile']->getClientOriginalName()]);

        try {
            // $hgImport->import($path, 'local', \Maatwebsite\Excel\Excel::XLSX);
            Log::info('validating import data.', ['league-id' => $league->id]);
            if ($league->is_custom) {
                $hgImport = new LeagueCustomGamesImport($league);
            } else {
                $hgImport = new LeagueGamesImport($league);
            }
            Excel::import($hgImport, $request->gfile->store('temp'));
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = Arr::sortRecursive($e->failures());
            $ebag = [];
            $frow = 0;
            foreach ($failures as $failure) {
                if ($frow != $failure->row()) {
                    $ebag[] = '---';
                }
                $ebag[] = __('import.row').' "'.$failure->row().'", '.__('import.column').' "'.$failure->attribute().'": '.$hgImport->buildValidationMessage($failure->errors()[0], $failure->values(), $failure->attribute());
                $frow = $failure->row();
            }
            Log::warning('errors found in import data.', ['count' => count($failures)]);

            return redirect()->back()->withErrors($ebag);
        }

        return redirect()->back()->with(['status' => 'All data imported']);
        //return redirect()->route('club.list.homegame', ['language'=>$language, 'club' => $club]);
    }
}
