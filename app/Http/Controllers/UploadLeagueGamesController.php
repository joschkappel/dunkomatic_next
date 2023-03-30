<?php

namespace App\Http\Controllers;

use App\Models\League;
use App\Imports\LeagueGamesImport;
use App\Imports\LeagueCustomGamesImport;

use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;

class UploadLeagueGamesController extends Controller
{


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
