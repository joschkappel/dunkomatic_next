<?php

namespace App\Http\Controllers;

use App\Models\Region;

use App\Imports\CustomLeagueGameImport;

use App\Traits\ImportErrorHandler;

use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Arr;

class UploadCustomLeaguesGamesController extends Controller
{
    use ImportErrorHandler;

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
                return back()->withErrors($ebag, 'default');
            } elseif ($fileType == 'xlsx') {
                // if excel return markedup excel file
                $ebag = $this->excelValidationErrors($request->gfile, Arr::sortRecursive($e->failures()));
                return back()->withErrors($ebag, 'file');
            } else {
                return back()->withErrors(['something went  horribly wrong :-('], 'default');
            }
        }

        return redirect()->back()->with(['status' => 'All data imported']);
    }
}
