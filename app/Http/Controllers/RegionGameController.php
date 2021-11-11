<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Region;
use App\Imports\RefereesImport;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class RegionGameController extends Controller
{
    /**
     * Show the form for uploading game files
     *
     * @param  \App\Models\Club  $club
     * @return \Illuminate\Http\Response
     */
    public function upload($language, Region $region)
    {
        Log::info('preparing file upload form for region.', ['region-id'=> $region->id ]);
        $cardtitle =  __('region.title.refgame.import', ['region'=>$region->code]);
        $uploadroute = route('region.import.refgame',['language'=> app()->getLocale(),'region' => $region]);
        return view('game/game_file_upload', ['cardTitle' => $cardtitle, 'uploadRoute'=>$uploadroute]);
    }

    /**
     * update games with referees from file
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Region $region
     * @return \Illuminate\Http\Response
     */
    public function import_referees(Request $request, $language, Region $region)
    {
        // Log::debug(print_r($request->all(),true));
        //$fname = $request->gfile->getClientOriginalName();
        //$fname = $club->shortname.'_homegames.'.$request->gfile->extension();
        $errors = [];
        Log::info('processing file upload.', ['region-id'=> $region->id, 'file'=> $request->gfile->getClientOriginalName() ]);

        $path = $request->gfile->store($region->code.'referees');
        $refImport = new RefereesImport($region);
        try {
          // $hgImport->import($path, 'local', \Maatwebsite\Excel\Excel::XLSX);
          Log::info('validating import data.', ['region-id'=> $region->id]);
          $refImport->import($path, 'local' );
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
          $failures = $e->failures();
          $ebag = array();
          foreach ($failures as $failure) {
              $ebag[] = 'Zeile '.$failure->row().', Spalte '.$failure->attribute().', Wert  ": '.$failure->errors()[0];
          }
          Log::warning('errors found in import data.', ['count'=> count($failures) ]);
          Storage::delete($path);
          return redirect()->back()->withErrors($ebag);
        }
        Storage::delete($path);

        return redirect()->back()->with(['status'=>'All data imported']);
    }

}
