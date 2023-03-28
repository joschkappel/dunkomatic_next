<?php

namespace App\Http\Controllers;

use App\Imports\RefereesImport;
use App\Imports\CustomLeagueGameImport;
use App\Models\Region;
use App\Traits\ImportErrorHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Arr;
use Maatwebsite\Excel\Facades\Excel;

class RegionGameController extends Controller
{
    use ImportErrorHandler;

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
    public function import_referees(Request $request, $language, Region $region)
    {
        // Log::debug(print_r($request->all(),true));
        //$fname = $request->gfile->getClientOriginalName();
        //$fname = $club->shortname.'_homegames.'.$request->gfile->extension();
        Log::info('processing file upload.', ['region-id' => $region->id, 'file' => $request->gfile->getClientOriginalName()]);

        try {
            Log::info('validating import data.', ['region-id' => $region->id]);
            $hgImport = new RefereesImport();
            Excel::import($hgImport, $request->gfile->store('temp'));
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $ebag = [];
            foreach ($failures as $failure) {
                $ebag[] = __('import.row').' "'.$failure->row().'", '.__('import.column').' "'.$failure->attribute().': '.$failure->errors()[0];
            }
            Log::warning('errors found in import data.', ['count' => count($failures)]);

            return redirect()->back()->withErrors($ebag);
        }

        return redirect()->back()->with(['status' => 'All data imported']);
    }

    /**
     * create or update games for custom leagues
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $language
     * @param  \App\Models\Region  $region
     * @return \Illuminate\Http\RedirectResponse
     */
    public function import(Request $request, $language, Region $region)
    {
        Log::info('processing file upload.', ['region-id' => $region->id, 'file' => $request->gfile->getClientOriginalName()]);

        try {
            Log::info('validating import data.', ['region-id' => $region->id]);
            $gImport = new CustomLeagueGameImport($region);
            Excel::import($gImport, $request->gfile->store('temp'));
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {

            $fileType = $request->gfile->getClientOriginalExtension();
            if ($fileType  == 'csv') {
                // if CSV do HTML return
                $ebag = $this->detailedHtmlErrors(Arr::sortRecursive($e->failures()));
            } elseif ($fileType == 'xlsx') {
                // if excel return markedup excel file
                $ebag = $this->excelValidationErrors($request->gfile, Arr::sortRecursive($e->failures()));
            }

            return redirect()->back()->withErrors($ebag);
        }

        return redirect()->back()->with(['status' => 'All data imported']);
    }
}
