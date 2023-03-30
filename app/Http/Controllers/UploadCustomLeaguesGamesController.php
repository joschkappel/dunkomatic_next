<?php

namespace App\Http\Controllers;

use App\Models\Region;
use App\Imports\CustomLeagueGameImport;
use App\Traits\ImportManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UploadCustomLeaguesGamesController extends Controller
{
    use ImportManager;

    /**
     * create or update games for custom leagues
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $language
     * @param  \App\Models\Region  $region
     * @return \Illuminate\Http\RedirectResponse
     */
    public function importAllLeagues(Request $request, $language, Region $region)
    {
        $data = $request->validate([
            'gfile' => 'required',
        ]);

        Log::info('processing file upload.', ['region-id' => $region->id, 'file' => $request->gfile->getClientOriginalName()]);

        $gImport = new CustomLeagueGameImport($region);
        [$importOk, $errors, $bagName] = $this->importGames($data['gfile'], $gImport);

        if ($importOk) {
            return back()->with($errors);
        } else {
            return back()->withErrors($errors, $bagName);
        }

    }
}
