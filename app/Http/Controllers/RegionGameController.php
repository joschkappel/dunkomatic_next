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

        $path = $request->gfile->store($region->code.'referees');
        $refImport = new RefereesImport($region);
        try {
          // $hgImport->import($path, 'local', \Maatwebsite\Excel\Excel::XLSX);
          $refImport->import($path, 'local' );
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
          $failures = $e->failures();
          $ebag = array();
          foreach ($failures as $failure) {
              $ebag[] = 'Zeile '.$failure->row().', Spalte '.$failure->attribute().', Wert  ": '.$failure->errors()[0];
          }
          Log::debug(print_r($ebag,true));
          Storage::delete($path);
          return redirect()->back()->withErrors($ebag);
        }
        Storage::delete($path);

        return redirect()->back()->with(['status'=>'All data imported']);
    }

}
