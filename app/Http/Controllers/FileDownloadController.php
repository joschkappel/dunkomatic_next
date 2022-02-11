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
use App\Models\Region;

class FileDownloadController extends Controller
{
    public function get_file(Request $request)
    {
        $data = $request->validate([
            'type' => 'required|in:App\Models\Club,App\Models\League',
            'club' => 'sometimes|required_if:league,null|exists:App\Models\Club,id',
            'league' => 'sometimes|required_if:club,null|exists:App\Models\League,id',
            'file' => 'required|string',
        ]);
        Log::info('get file data validated OK.');

        if ( $data['type'] == Club::class){
            $filepath = Club::findOrFail($data['club'])->region->club_folder;
        } elseif ( $data['type'] == League::class){
            $filepath = League::findOrFail($data['league'])->region->league_folder;
        } else {
            return back();
        }
        $filepath .= '/'.$data['file'];
        Log::info('downloading files.',['path'=> $filepath]);
        if ( Storage::disk('public')->exists($data['file'])){
            Storage::disk('public')->delete($data['file']);
        }
        Storage::disk('public')->writeStream(
            $data['file'],
            Storage::disk('exports')->readStream($filepath)
        );
        return Storage::disk('public')->download($data['file'], $data['file']);
    }

    public function get_user_archive(Region $region, User $user)
    {
        Log::info('user file archive download.', ['user-id' => $user->id, 'region-id' => $region->id]);

        if ($user->LeagueFilecount($region) + $user->ClubFilecount($region) + $user->TeamwareFilecount($region) > 0) {
            $zip = new ZipArchive;
            $fileName = $region->code . '-reports-' . Str::slug($user->name, '-') . '.zip';
            Storage::disk('public')->delete( $fileName);

            $pf = Storage::disk('public')->path($fileName);
            Log::info('archive location.',['path'=>$pf]);

            if ($zip->open($pf, ZipArchive::CREATE) === TRUE) {
                $files = $user->LeagueFilenames($region);
                $files = $files->concat($user->ClubFilenames($region));
                $files = $files->concat($user->TeamwareFilenames($region));

                foreach ($files as $f) {
                    $f =  Storage::disk('exports')->path($f);
                    $check = $zip->addFile($f, basename($f));
                }

                $zip->close();
                //  Storage::move(public_path($fileName), 'public/'.$fileName);
                Log::notice('downloading ZIP archive for user', ['user-id'=>$user->id, 'filecount'=>count($files)]);

                return Storage::download($fileName);
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
            $pf = Storage::disk('public')->path($filename);
            Log::info('archive location.',['path'=>$pf]);

            if ($zip->open($pf, ZipArchive::CREATE) === TRUE) {
                $files = $club->filenames;

                foreach ($files as $f) {
                    $f = Storage::disk('exports')->path($f);
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
            $pf = Storage::disk('public')->path($filename);
            Log::info('archive location.',['path'=>$pf]);

            if ($zip->open($pf, ZipArchive::CREATE) === TRUE) {
                $files = $league->filenames;
                Log::debug(print_r($files, true));

                foreach ($files as $f) {
                    $f =  Storage::disk('exports')->path($f);
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

    public function get_region_league_archive(Region $region)
    {
        Log::info('region league file archive download.', ['region-id' => $region->id]);

        if ($region->league_filecount > 0) {
            $zip = new ZipArchive;
            $filename = $region->code . '-runden-reports.zip';
            $pf = Storage::disk('public')->path($filename);
            Log::info('archive location.',['path'=>$pf]);

            if ($zip->open($pf, ZipArchive::CREATE) === TRUE) {
                $files = $region->teamware_filenames;
                Log::debug(print_r($files, true));

                foreach ($files as $f) {
                    $f =  Storage::disk('exports')->path($f);
                    $check = $zip->addFile($f, basename($f));
                }

                $zip->close();
                //  Storage::move(public_path($fileName), 'public/'.$fileName);
                Log::notice('downloading ZIP archive for region teamware', ['region-id'=>$region->id, 'filecount'=>count($files)]);

                return Storage::download('public/' . $filename);
            }
        } else {
            Log::error('no files found for region.', ['region-id' => $region->id]);
            return abort(404);
        }
    }

    public function get_region_teamware_archive(Region $region)
    {
        Log::info('region teamware file archive download.', ['region-id' => $region->id]);

        if ($region->teamware_filecount > 0) {
            $zip = new ZipArchive;
            $filename = $region->code . '-teamware-reports.zip';
            $pf = Storage::disk('public')->path($filename);
            Log::info('archive location.',['path'=>$pf]);

            if ($zip->open($pf, ZipArchive::CREATE) === TRUE) {
                $files = $region->league_filenames;
                Log::debug(print_r($files, true));

                foreach ($files as $f) {
                    $f = Storage::disk('exports')->path($f);
                    $check = $zip->addFile($f, basename($f));
                }

                $zip->close();
                //  Storage::move(public_path($fileName), 'public/'.$fileName);
                Log::notice('downloading ZIP archive for region leagues', ['region-id'=>$region->id, 'filecount'=>count($files)]);

                return Storage::download('public/' . $filename);
            }
        } else {
            Log::error('no files found for region.', ['region-id' => $region->id]);
            return abort(404);
        }
    }
}
