<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ZipArchive;

use App\Models\User;
use App\Models\Club;
use App\Models\League;

class FileDownloadController extends Controller
{
    public function get_file($season, $region, $type, $file)
    {
        Log::info('downloading files.',['path'=> 'exports/' . $season . '/' . $region . '/' . $type . '/' . $file]);
        return Storage::download('exports/' . $season . '/' . $region . '/' . $type . '/' . $file, $file);
    }

    public function get_user_archive(User $user)
    {
        Log::info('user file archive download.', ['user-id' => $user->id]);

        if ($user->league_filecount + $user->club_filecount > 0) {
            $zip = new ZipArchive;
            $fileName = $user->region->code . '-reports-' . Str::slug($user->name, '-') . '.zip';
            Storage::delete('public/' . $fileName);

            $pf = storage_path('app/public/' . $fileName);
            Log::info('archive location.',['path'=>$pf]);

            if ($zip->open($pf, ZipArchive::CREATE) === TRUE) {
                $files = $user->league_filenames;
                $files = $files->concat($user->club_filenames);

                foreach ($files as $f) {
                    $f =  storage_path('app/' . $f);
                    $check = $zip->addFile($f, basename($f));
                }

                $zip->close();
                //  Storage::move(public_path($fileName), 'public/'.$fileName);
                Log::notice('downloading ZIP archive for user', ['user-id'=>$user->id, 'filecount'=>count($files)]);

                return Storage::download('public/' . $fileName);
            }
        } else {
            Log::error('no files found for user.', ['user-id' => $user->id]);
            return abort(404);
        }
    }

    public function get_club_archive(Club $club)
    {
        Log::info('club file archive download.', ['club-id' => $club->id]);

        if ($club->filecount > 0) {
            $zip = new ZipArchive;
            $filename = $club->region->code . '-reports-' . Str::slug($club->shortname, '-') . '.zip';
            $pf = storage_path('app/public/' . $filename);
            Log::info('archive location.',['path'=>$pf]);

            if ($zip->open($pf, ZipArchive::CREATE) === TRUE) {
                $files = $club->filenames;

                foreach ($files as $f) {
                    $f =  storage_path('app/' . $f);
                    $check = $zip->addFile($f, basename($f));
                }

                $zip->close();
                //  Storage::move(public_path($fileName), 'public/'.$fileName);
                Log::notice('downloading ZIP archive for club', ['club-id'=>$club->id, 'filecount'=>count($files)]);

                return Storage::download('public/' . $filename);
            }
        } else {
            Log::error('no files found for club.', ['club-id' => $club->id]);
            return abort(404);
        }
    }

    public function get_league_archive(League $league)
    {
        Log::info('league file archive download.', ['league-id' => $league->id]);

        if ($league->filecount > 0) {
            $zip = new ZipArchive;
            $filename = $league->region->code . '-reports-' . Str::slug($league->shortname, '-') . '.zip';
            $pf = storage_path('app/public/' . $filename);
            Log::info('archive location.',['path'=>$pf]);

            if ($zip->open($pf, ZipArchive::CREATE) === TRUE) {
                $files = $league->filenames;
                Log::debug(print_r($files, true));

                foreach ($files as $f) {
                    $f =  storage_path('app/' . $f);
                    $check = $zip->addFile($f, basename($f));
                }

                $zip->close();
                //  Storage::move(public_path($fileName), 'public/'.$fileName);
                Log::notice('downloading ZIP archive for league', ['league-id'=>$league->id, 'filecount'=>count($files)]);

                return Storage::download('public/' . $filename);
            }
        } else {
            Log::error('no files found for league.', ['league-id' => $league->id]);
            return abort(404);
        }
    }
}
