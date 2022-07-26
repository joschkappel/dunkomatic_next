<?php

namespace App\Http\Controllers;

use App\Enums\ReportFileType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ZipArchive;

use App\Models\User;
use App\Models\Club;
use App\Models\League;
use App\Models\Region;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;

class FileDownloadController extends Controller
{
    /**
     * download a single file
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\StreamedResponse|\Illuminate\Http\RedirectResponse
     *
     */
    public function get_file(Request $request)
    {
        Log::debug('input',['request'=>$request->input()]);
        $data = $request->validate([
            'type' => 'required|in:App\Models\Club,App\Models\League',
            'club' => 'required_without:league|exists:App\Models\Club,id',
            'league' => 'required_without:club|exists:App\Models\League,id',
            'file' => 'required|string',
        ]);
        Log::info('get file data validated OK.');

        if ($data['type'] == Club::class) {
            $filepath = Club::findOrFail($data['club'])->region->club_folder;
        } elseif ($data['type'] == League::class) {
            $filepath = League::findOrFail($data['league'])->region->league_folder;
        } else {
            return back();
        }
        $filepath .= '/'. $data['file'];
        Log::info('downloading file.', ['path' => $filepath]);
        Storage::disk('public')->delete($data['file']);
        Storage::disk('public')->writeStream(
            $data['file'],
            Storage::readStream($filepath)
        );
        // return Storage::disk('public')->download($data['file'], $data['file']);
        return response()->file(Storage::disk('public')->path($data['file']));
    }

    /**
     * download archive with a users reports
     *
     * @param \App\Models\Region $region
     * @param \App\Models\User $user
     * @return \Symfony\Component\HttpFoundation\StreamedResponse|\Illuminate\Http\RedirectResponse
     *
     */
    public function get_user_archive(Region $region, User $user)
    {
        Log::info('user file archive download.', ['user-id' => $user->id, 'region-id' => $region->id]);

        if ($user->LeagueFilecount($region) + $user->ClubFilecount($region) + $user->TeamwareFilecount($region) > 0) {
            $zip = new ZipArchive;
            $fileName = $region->code . '-reports-' . Str::slug($user->name, '-') . '.zip';
            Storage::disk('public')->delete($fileName);
            $pf = Storage::disk('public')->path($fileName);
            Log::info('archive location.', ['path' => $pf]);

            if ($zip->open($pf, ZipArchive::CREATE) === TRUE) {
                $files = $user->LeagueFilenames($region);
                $files = $files->concat($user->ClubFilenames($region));
                $files = $files->concat($user->TeamwareFilenames($region));

                foreach ($files as $f) {
                    $check = $zip->addFromString(basename($f), Storage::get($f));
                }

                $zip->close();
                //  Storage::move(public_path($fileName), 'public/'.$fileName);
                Log::notice('downloading ZIP archive for user', ['user-id' => $user->id, 'filecount' => count($files)]);

                return Storage::disk('public')->download($fileName);
            } else {
                Log::error('archive corrupt.', ['user-id' => $user->id]);
                return abort(500);
            }
        } else {
            Log::error('no files found for user.', ['user-id' => $user->id]);
            return abort(404);
        }
    }

    /**
     * download archive with a users reports
     *
     * @param \App\Models\Club $club
     * @param \App\Enums\ReportFileType $format
     * @return \Symfony\Component\HttpFoundation\StreamedResponse|\Illuminate\Http\RedirectResponse
     *
     */
    public function get_club_archive(Club $club, int $format)
    {
        $format = ReportFileType::coerce($format);
        Log::info('club file archive download.', ['club-id' => $club->id,'format'=>$format]);

        if ($club->filecount_for_type($format) > 0) {
            $zip = new ZipArchive;
            $filename = $club->region->code . '-reports-' . Str::slug($club->shortname, '-') . '.zip';
            Storage::disk('public')->delete($filename);
            $pf = Storage::disk('public')->path($filename);
            Log::info('archive location.', ['path' => $pf]);

            if ($zip->open($pf, ZipArchive::CREATE) === TRUE) {
                $files = $club->filenames_for_type($format);

                foreach ($files as $f) {
                    $check = $zip->addFromString(basename($f), Storage::get($f));
                }

                $zip->close();
                Log::notice('downloading ZIP archive for club', ['club-id' => $club->id, 'filecount' => count($files)]);

                return Storage::disk('public')->download( $filename);
            } else {
                Log::error('archive corrupt.', ['club-id' => $club->id]);
                return abort(500);
            }
        } else {
            Log::error('no files found for club.', ['club-id' => $club->id]);
            return Redirect::back()->withErrors(['format' => $format->key]);
        }
    }

    /**
     * download archive with leagues reports
     *
     * @param \App\Models\League $league
     * @param \App\Enums\ReportFileType $format
     * @return \Symfony\Component\HttpFoundation\StreamedResponse|\Illuminate\Http\RedirectResponse
     *
     */

    public function get_league_archive(League $league, int $format)
    {
        $format = ReportFileType::coerce($format);
        Log::info('league file archive download.', ['league-id' => $league->id,'format'=>$format]);

        if ($league->filecount_for_type($format) > 0) {
            $zip = new ZipArchive;
            $filename = $league->region->code . '-reports-' . Str::slug($league->shortname, '-') . '.zip';
            Storage::disk('public')->delete($filename);
            $pf = Storage::disk('public')->path($filename);
            Log::info('archive location.', ['path' => $pf]);

            if ($zip->open($pf, ZipArchive::CREATE) === TRUE) {
                $files = $league->filenames_for_type($format);

                foreach ($files as $f) {
                    $check = $zip->addFromString(basename($f), Storage::get($f));
                }

                $zip->close();
                //  Storage::move(public_path($fileName), 'public/'.$fileName);
                Log::notice('downloading ZIP archive for league', ['league-id' => $league->id, 'filecount' => count($files)]);

                return Storage::disk('public')->download( $filename);
            } else {
                Log::error('archive corrupt.', ['league-id' => $league->id]);
                return abort(500);
            }
        } else {
            Log::error('no files found for league.', ['league-id' => $league->id]);
            return Redirect::back()->withErrors(['format' => $format->key]); //abort(404);
        }
    }
    /**
     * download archive with region reports
     *
     * @param \App\Models\Region $region
     * @param \App\Enums\ReportFileType $format
     * @return \Symfony\Component\HttpFoundation\StreamedResponse|\Illuminate\Http\RedirectResponse
     *
     */

    public function get_region_archive(Region $region, int $format)
    {
        $format = ReportFileType::coerce($format);
        Log::info('region file archive download.', ['region-id' => $region->id,'format'=>$format]);

        if ($region->filecount_for_type($format) > 0) {
            $zip = new ZipArchive;
            $filename = $region->code . '-reports.zip';
            Storage::disk('public')->delete($filename);
            $pf = Storage::disk('public')->path($filename);
            Log::info('archive location.', ['path' => $pf]);

            if ($zip->open($pf, ZipArchive::CREATE) === TRUE) {
                $files = $region->filenames_for_type($format);

                foreach ($files as $f) {
                    $check = $zip->addFromString(basename($f), Storage::get($f));
                }

                $zip->close();
                //  Storage::move(public_path($fileName), 'public/'.$fileName);
                Log::notice('downloading ZIP archive for region', ['region-id' => $region->id, 'filecount' => count($files)]);

                return Storage::disk('public')->download( $filename);
            } else {
                Log::error('archive corrupt.', ['region-id' => $region->id]);
                return abort(500);
            }
        } else {
            Log::error('no files found for region.', ['region-id' => $region->id]);
            return Redirect::back()->withErrors(['format' => $format->key]);
        }
    }
    /**
     * download archive with regino reports
     *
     * @param \App\Models\Region $region
     * @return \Symfony\Component\HttpFoundation\StreamedResponse|\Illuminate\Http\RedirectResponse
     *
     */
    public function get_region_league_archive(Region $region)
    {
        Log::info('region league file archive download.', ['region-id' => $region->id]);

        if ($region->league_filecount > 0) {
            $zip = new ZipArchive;
            $filename = $region->code . '-runden-reports.zip';
            Storage::disk('public')->delete($filename);
            $pf = Storage::disk('public')->path($filename);
            Log::info('archive location.', ['path' => $pf]);

            if ($zip->open($pf, ZipArchive::CREATE) === TRUE) {
                $files = $region->league_filenames;

                foreach ($files as $f) {
                    $check = $zip->addFromString(basename($f), Storage::get($f));
                }

                $zip->close();
                //  Storage::move(public_path($fileName), 'public/'.$fileName);
                Log::notice('downloading ZIP archive for region leagues', ['region-id' => $region->id, 'filecount' => count($files)]);

                return Storage::disk('public')->download($filename);
            } else {
                Log::error('archive corrupt.', ['region-id' => $region->id]);
                return abort(500);
            }
        } else {
            Log::error('no files found for region.', ['region-id' => $region->id]);
            return abort(404);
        }
    }

    /**
     * download archive with region teamware reports
     *
     * @param \App\Models\Region $region
     * @return \Symfony\Component\HttpFoundation\StreamedResponse|\Illuminate\Http\RedirectResponse
     *
     */
    public function get_region_teamware_archive(Region $region)
    {
        Log::info('region teamware file archive download.', ['region-id' => $region->id]);

        if ($region->teamware_filecount > 0) {
            $zip = new ZipArchive;
            $filename = $region->code . '-teamware-reports.zip';
            Storage::disk('public')->delete($filename);
            $pf = Storage::disk('public')->path($filename);
            Log::info('archive location.', ['path' => $pf]);

            if ($zip->open($pf, ZipArchive::CREATE) === TRUE) {
                $files = $region->teamware_filenames;

                foreach ($files as $f) {
                    $check = $zip->addFromString(basename($f), Storage::get($f));
                }

                $zip->close();
                //  Storage::move(public_path($fileName), 'public/'.$fileName);
                Log::notice('downloading ZIP archive for region teamware', ['region-id' => $region->id, 'filecount' => count($files)]);

                return Storage::disk('public')->download($filename);
            } else {
                Log::error('archive corrupt.', ['region-id' => $region->id]);
                return abort(500);
            }
        } else {
            Log::error('no files found for region.', ['region-id' => $region->id]);
            return abort(404);
        }
    }
}
