<?php

namespace App\Http\Controllers;

use App\Models\Region;
use App\Imports\RefereesImport;

use Spatie\TemporaryDirectory\TemporaryDirectory;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RegionGameController extends Controller
{
    /**
     * Show the form for uploading game files
     *
     * @param string $language
     * @param  \App\Models\Region  $region
     * @return \Illuminate\View\View
     *
     */
    public function upload($language, Region $region)
    {
        Log::info('preparing file upload form for region.', ['region-id'=> $region->id ]);
        $cardtitle =  __('region.title.refgame.import', ['region'=>$region->code]);
        $uploadroute = route('region.import.refgame',['language'=> app()->getLocale(),'region' => $region]);
        return view('game/game_file_upload', ['cardTitle' => $cardtitle, 'uploadRoute'=>$uploadroute, 'context'=>'referee']);
    }

    /**
     * update games with referees from file
     *
     * @param  \Illuminate\Http\Request  $request
     * @param string $language
     * @param  \App\Models\Region $region
     * @return \Illuminate\Http\RedirectResponse
     *
     */
    public function import_referees(Request $request, $language, Region $region)
    {
        // Log::debug(print_r($request->all(),true));
        //$fname = $request->gfile->getClientOriginalName();
        //$fname = $club->shortname.'_homegames.'.$request->gfile->extension();
        Log::info('processing file upload.', ['region-id'=> $region->id, 'file'=> $request->gfile->getClientOriginalName() ]);

        $tmpDir = (new TemporaryDirectory())->create();
        $path = $request->gfile->store($tmpDir->path());
        $refImport = new RefereesImport();
        try {
          // $hgImport->import($path, 'local', \Maatwebsite\Excel\Excel::XLSX);
          Log::info('validating import data.', ['region-id'=> $region->id]);
          $refImport->import($path, 'local' );
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
          $failures = $e->failures();
          $ebag = array();
          foreach ($failures as $failure) {
              $ebag[] = __('import.row').' "'.$failure->row().'", '.__('import.column').' "'.$failure->attribute().': '.$failure->errors()[0];
          }
          Log::warning('errors found in import data.', ['count'=> count($failures) ]);
          $tmpDir->delete();
          return redirect()->back()->withErrors($ebag);
        }
        $tmpDir->delete();

        return redirect()->back()->with(['status'=>'All data imported']);
    }

}
