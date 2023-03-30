<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Imports\HomeGamesImport;
use App\Traits\ImportManager;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class UploadClubGamesController extends Controller
{
    use ImportManager;

    /**
     * Show the form for uploading game files
     *
     * @param  string  $language
     * @param  \App\Models\Club  $club
     * @return \Illuminate\View\View
     */
    public function upload(string $language, Club $club)
    {
        Log::info('preparing file upload form for club.', ['club-id' => $club->id]);

        $cardtitle = __('club.title.gamehome.import', ['club' => $club->shortname]);
        $uploadroute = route('club.import.homegame', ['language' => app()->getLocale(), 'club' => $club]);
        $context = 'club';

        return view('game.game_file_upload', ['cardTitle' => $cardtitle, 'uploadRoute' => $uploadroute, 'context' => $context]);
    }

    /**
     * update games with file contents
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $language
     * @param  \App\Models\Club  $club
     * @return \Illuminate\Http\RedirectResponse
     */
    public function import(Request $request, $language, Club $club)
    {
        $data = $request->validate([
            'gfile' => 'required',
        ]);
        Log::info('processing file upload.', ['club-id' => $club->id, 'file' => $data['gfile']->getClientOriginalName()]);

        $hgImport = new HomeGamesImport();

        [$importOk, $errors, $bagName] = $this->importGames($data['gfile'], $hgImport);

        if ($importOk) {
            return back()->with($errors);
        } else {
            return back()->withErrors($errors, $bagName);
        }

    }
}
