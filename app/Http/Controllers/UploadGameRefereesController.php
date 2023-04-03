<?php

namespace App\Http\Controllers;

use App\Imports\RefereesImport;
use App\Models\Region;
use App\Traits\ImportManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class UploadGameRefereesController extends Controller
{
    use ImportManager;

    /**
     * Show the form for uploading game files
     *
     * @param  string  $language
     * @param  \App\Models\Region  $region
     * @return \Illuminate\View\View
     */
    public function upload($language, Region $region)
    {
        Log::info('preparing file upload form for region.', ['region-id' => $region->id]);
        $cardtitle = __('region.title.refgame.import', ['region' => $region->code]);
        $uploadroute = route('region.import.refgame', ['language' => app()->getLocale(), 'region' => $region]);

        return view('game/game_file_upload', ['cardTitle' => $cardtitle, 'uploadRoute' => $uploadroute, 'context' => 'referee']);
    }

    /**
     * update games with referees from file
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $language
     * @param  \App\Models\Region  $region
     * @return \Illuminate\Http\RedirectResponse
     */
    public function import(Request $request, $language, Region $region)
    {

        $data = $request->validate([
            'gfile' => 'required',
        ]);
        Log::info('processing file upload.', ['region-id' => $region->id, 'file' => $request->gfile->getClientOriginalName()]);

        $hgImport = new RefereesImport();

        [$importOk, $errors, $bagName] = $this->importGames($data['gfile'], $hgImport);

        if ($importOk) {
            return back()->with($errors);
        } else {
            return back()->withErrors($errors, $bagName);
        }
    }
}
