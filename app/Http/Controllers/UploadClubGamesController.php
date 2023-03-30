<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Imports\HomeGamesImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class UploadClubGamesController extends Controller
{
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
        Log::info('upload form data validated OK.');
        Log::info('processing file upload.', ['club-id' => $club->id, 'file' => $data['gfile']->getClientOriginalName()]);
        // Log::debug(print_r($request->all(),true));
        //$fname = $request->gfile->getClientOriginalName();
        //$fname = $club->shortname.'_homegames.'.$request->gfile->extension();

        try {
            // $hgImport->import($path, 'local', \Maatwebsite\Excel\Excel::XLSX);
            Log::info('validating import data.', ['club-id' => $club->id]);
            $hgImport = new HomeGamesImport();
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
