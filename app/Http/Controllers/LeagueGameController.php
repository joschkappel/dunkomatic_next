<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\League;
use App\Models\Gym;
use App\Models\Team;
use App\Traits\LeagueFSM;
use App\Traits\GameManager;
use App\Imports\LeagueGamesImport;


use Datatables;
use Carbon\Carbon;
use Spatie\TemporaryDirectory\TemporaryDirectory;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Arr;


class LeagueGameController extends Controller
{
    use LeagueFSM;

    /**
     * Display a listing of the resource.
     *
     * @param  \App\Models\League  $league
     * @return \Illuminate\Http\Response
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
     * @param  $game_no
     * @return \Illuminate\Http\Response
     */
    public function show_by_number(League $league, $game_no)
    {
        Log::info('getting a game.', ['league-id' => $league->id, 'game-no' => $game_no]);
        $game = $league->games->where('game_no', $game_no)->first();

        return Response::json($game, 200);
    }

    public function datatable($language, League $league)
    {
        Log::info('preparing game list', ['league-id' => $league->id]);

        $games = $league->games()->with('league')->get();
        $glist = datatables()::of($games);

        $glist =  $glist
            ->rawColumns(['game_no.display'])
            ->editColumn('game_time', function ($game) {
                return ($game->game_time == null) ? '' : Carbon::parse($game->game_time)->isoFormat('LT');
            })
            ->editColumn('game_no', function ($game) {
                $link = '<a href="#" id="gameEditLink" data-id="' . $game->id .
                    '" data-game-date="' . $game->game_date . '" data-game-time="' . $game->game_time . '" data-club-id-home="' . $game->club_id_home .
                    '" data-gym-no="' . $game->gym_no . '" data-gym-id="' . $game->gym_id . '" data-league="' . $game->league['shortname'] .
                    '" data-team-home="' . $game->team_home . '" data-team-id-home="' . $game->team_id_home . '" data-team-guest="' . $game->team_guest . '" data-team-id-guest="' . $game->team_id_guest .
                    '" data-game-no="' . $game->game_no . '" data-league-id="' . $game->league_id .
                    '">' . $game->game_no . ' <i class="fas fa-arrow-circle-right"></i></a>';
                return array('display' => $link, 'sort' => $game->game_no);
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
                    'display' => $game->gym_no,
                    'default' => $game->gym_no
                );
            })
            ->make(true);
        //Log::debug(print_r($glist,true));
        return $glist;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Models\League  $league
     * @return \Illuminate\Http\Response
     */
    public function store(League $league)
    {
        Log::info('creating games.', ['league-id' => $league->id]);
        //$this->create_games($league);
        $this->close_freeze($league);

        return Response::json(['success' => 'all good'], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\League  $league
     * @param  \App\Models\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Game $game)
    {
        if ($game->league->schedule->custom_events) {
            Log::info('update game - custom schedule.', ['game-id' => $game->id]);
            $maxgames = $game->league->size * ($game->league->size - 1);

            $data = Validator::make($request->all(), [
                'gym_id' => 'required|exists:gyms,id',
                'game_date' => 'required|date|after:today',
                'game_time' => 'required|date_format:H:i',
                'team_id_home' => 'exists:teams,id|different:team_id_guest',
                'team_id_guest' => 'exists:teams,id|different:team_id_home',
                //     'game_no' => 'integer|between:1,12',
                'game_no' => [Rule::unique('games')->where(function ($query) use ($game) {
                    return $query->where('league_id', $game->league->id)->where('game_no', '!=', $game->game_no);
                }), 'integer', 'between:1,' . $maxgames]
            ])->validate();
            Log::info('game form data validate OK.', ['game-id' => $game->id]);

            // handle new home team
            if ($data['team_id_home'] != $game->team_id_home) {
                $team_home = Team::find($data['team_id_home']);
                $data['club_id_home'] = $team_home->club->id;
                $data['team_home'] = $team_home->club->shortname . $team_home->team_no;
            } else {
                unset($data['team_id_home']);
            }

            // handle new guest team
            if ($data['team_id_guest'] != $game->team_id_guest) {
                $team_guest = Team::find($data['team_id_guest']);
                $data['club_id_guest'] = $team_guest->club->id;
                $data['team_guest'] = $team_guest->club->shortname . $team_guest->team_no;
            } else {
                unset($data['team_id_guest']);
            }
        } else {
            $data = Validator::make($request->all(), [
                'gym_id' => 'required|exists:gyms,id',
                'game_date' => 'required|date|after:today',
                'game_time' => 'required|date_format:H:i'
            ])->validate();
            Log::info('game form data validate OK.', ['game-id' => $game->id]);
        }

        $data['game_time'] = Carbon::parse($data['game_time'])->format('H:i');

        // Get GYM NO
        $data['gym_no'] = Gym::findOrFail($data['gym_id'])->gym_no;

        $game->update($data);
        Log::notice('game updated.', ['game-id' => $game->id]);

        return response()->json(['success' => 'Data is successfully added']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\League  $league
     * @param  \App\Models\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function update_home(Request $request, Game $game)
    {
        $data = Validator::make($request->all(), [
            'gym_id' => 'required|exists:gyms,id',
            'game_date' => 'required|date|after:today',
            'game_time' => 'required|date_format:H:i'
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
     * @param  \App\Models\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function destroy_game(League $league)
    {
        $league->games()->delete();
        Log::notice('games deleted.', ['league-id' => $league->id]);

        $this->open_freeze($league);
        $league->refresh();

        return Response::json(['success' => 'all good'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\League  $league
     * @param  \App\Models\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function destroy_noshow_game(League $league)
    {
        $check = $league->games()->where(function (Builder $query) {
            return $query->whereNull('club_id_home')
                ->orWhereNull('club_id_guest');
        })->delete();
        Log::notice('no show games deleted.', ['league-id' => $league->id]);

        //Log::debug($check);
        return Response::json(['success' => 'all good'], 200);
    }

    /**
     * Show the form for uploading game files
     *
     * @param  \App\Models\League  $league
     * @return \Illuminate\Http\Response
     */
    public function upload($language, League $league)
    {
        Log::info('preparing file upload form for league.', ['league-id' => $league->id]);

        $cardtitle =  __('league.title.game.import', ['league' => $league->shortname]);
        $uploadroute = route('league.import.game', ['language' => app()->getLocale(), 'league' => $league]);

        return view('game.game_file_upload', ['cardTitle' => $cardtitle, 'uploadRoute' => $uploadroute, 'context' => 'league']);
    }

    /**
     * update imported games with file contents
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\League  $league
     * @return \Illuminate\Http\Response
     */
    public function import(Request $request, $language, League $league)
    {
        $data = $request->validate([
            'gfile' => 'required'
        ]);
        Log::info('upload form data validated OK.');
        Log::info('processing file upload.', ['league-id' => $league->id, 'file' => $data['gfile']->getClientOriginalName()]);

        $tmpDir = (new TemporaryDirectory())->create();
        $path = $data['gfile']->store($tmpDir->path());
        $hgImport = new LeagueGamesImport($league);
        try {
            // $hgImport->import($path, 'local', \Maatwebsite\Excel\Excel::XLSX);
            Log::info('validating import data.', ['league-id' => $league->id]);
            $hgImport->import($path, 'local');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = Arr::sortRecursive($e->failures());
            $ebag = array();
            $frow = 0;
            foreach ($failures as $failure) {
                if ($frow != $failure->row()) {
                    $ebag[] = '---';
                };
                $ebag[] = __('import.row') . ' "' . $failure->row() . '", ' . __('import.column') . ' "' . $failure->attribute() . '": ' . $hgImport->buildValidationMessage($failure->errors()[0], $failure->values(), $failure->attribute() );
                $frow = $failure->row();
            }
            Log::warning('errors found in import data.', ['count' => count($failures)]);
            $tmpDir->delete();
            return redirect()->back()->withErrors($ebag);
        }
        $tmpDir->delete();

        return redirect()->back()->with(['status' => 'All data imported']);
        //return redirect()->route('club.list.homegame', ['language'=>$language, 'club' => $club]);
    }
}
