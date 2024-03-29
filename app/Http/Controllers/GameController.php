<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Region;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class GameController extends Controller
{

    /**
     * view with all games for a regions
     *
     * @param  string  $language
     * @param  \App\Models\Region  $region
     * @return \Illuminate\View\View
     */
    public function index($language, Region $region)
    {
        Log::info('showing game list.');

        return view('game/game_list', ['region' => $region]);
    }

    /**
     * datatables.net  with all games for a regions
     *
     * @param  string  $language
     * @param  \App\Models\Region  $region
     * @return \Illuminate\Http\JsonResponse
     */
    public function datatable($language, Region $region)
    {
        if ($region->is_base_level) {
            //$games = Game::whereIn('club_id_home', $clubs)->orWhereIn('club_id_guest',$clubs)->orderBy('game_date')->get();
            $games = Game::where('region_id_home', $region->id)->orWhere('region_id_guest', $region->id)->with(['league.region', 'gym', 'team_home.club.region', 'team_guest.club.region'])->orderBy('game_date')->get();
        } else {
            $games = Game::where('region_id_league', $region->id)->with(['league', 'gym', 'team_home.club.region', 'team_guest.club.region'])->orderBy('game_date')->get();
        }

        Log::info('preparing game list');
        $glist = datatables()::of($games->unique());

        $glist = $glist
            ->editColumn('game_time', function ($game) {
                return ($game->game_time == null) ? '' : Carbon::parse($game->game_time)->isoFormat('LT');
            })
            ->editColumn('game_no', function ($game) {
                return ['display' => $game->game_no, 'sort' => $game->game_no];
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
                    'display' => ($game->gym_no ?? '?').' - '.($game['gym']->name ?? '?'),
                    'default' => ($game->gym_no ?? '?'),
                ];
            })
            ->addColumn('game_league', function ($game) {
                return $game->league;
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
     * view to upload game files
     *
     * @param  string  $language
     * @param  \App\Models\Region  $region
     * @return \Illuminate\View\View
     */
    public function upload($language, Region $region)
    {
        Log::info('showing game upload view.');
        $uploadroute = route('region.import.customgame', ['language' => $language, 'region' => $region]);

        return view('game.game_file_upload', ['cardTitle' =>  __('region.title.game.import'), 'uploadRoute' => $uploadroute, 'context' => 'customgames']);
    }
}
