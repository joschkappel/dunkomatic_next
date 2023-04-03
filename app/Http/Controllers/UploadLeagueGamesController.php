<?php

namespace App\Http\Controllers;

use App\Models\League;
use App\Imports\LeagueGamesImport;
use App\Imports\LeagueCustomGamesImport;
use App\Traits\ImportManager;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;

class UploadLeagueGamesController extends Controller
{

    use ImportManager;

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
        Log::info('processing file upload.', ['league-id' => $league->id, 'file' => $data['gfile']->getClientOriginalName()]);

        if ($league->is_custom) {
            $hgImport = new LeagueCustomGamesImport($league);
        } else {
            $hgImport = new LeagueGamesImport($league);
        }

        [$importOk, $errors, $bagName] = $this->importGames($data['gfile'], $hgImport);

        if ($importOk) {
            return back()->with($errors);
        } else {
            return back()->withErrors($errors, $bagName);
        }

    }
}
