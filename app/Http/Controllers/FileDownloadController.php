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
    return Storage::download('exports/'.$season.'/'.$region.'/'.$type.'/'.$file, $file);
  }

  public function get_user_archive(User $user)
  {
    Log::info('User '.$user->name.' wants to download an archive');


    if ( $user->league_filecount + $user->club_filecount > 0){
      $zip = new ZipArchive;
      $fileName = $user->region->code.'-reports-'.Str::slug($user->name,'-').'.zip';
      Storage::delete('public/'.$fileName);

      $pf = storage_path('app/public/'.$fileName);
      //Log::info(public_path($pf));

      if ($zip->open($pf, ZipArchive::CREATE) === TRUE)
      {
          $files = $user->league_filenames;
          $files = $files->concat($user->club_filenames);
          Log::debug(print_r($files,true));

          foreach ($files as $f) {
              $f =  storage_path('app/'.$f);
              $check = $zip->addFile($f, basename($f));
          }

          $zip->close();
        //  Storage::move(public_path($fileName), 'public/'.$fileName);

          return Storage::download('public/'.$fileName);
      }
    } else {
      return abort(404);
    }
  }

  public function get_club_archive(Club $club)
  {
    Log::info('Club '.$club->shortname.' wants to download an archive');


    if ( $club->filecount > 0){
      $zip = new ZipArchive;
      $filename = $club->region->code.'-reports-'.Str::slug($club->shortname,'-').'.zip';
      $pf = storage_path('app/public/'.$filename);
      Log::info($pf);

      if ($zip->open($pf, ZipArchive::CREATE) === TRUE)
      {
          $files = $club->filenames;
          Log::debug(print_r($files,true));

          foreach ($files as $f) {
              $f =  storage_path('app/'.$f);
              $check = $zip->addFile($f, basename($f));
          }

          $zip->close();
        //  Storage::move(public_path($fileName), 'public/'.$fileName);

          return Storage::download('public/'.$filename);
      }
    } else {
      return abort(404);
    }
  }

  public function get_league_archive(League $league)
  {
    Log::info('League '.$league->shortname.' wants to download an archive');


    if ( $league->filecount > 0){
      $zip = new ZipArchive;
      $filename = $league->region->code.'-reports-'.Str::slug($league->shortname,'-').'.zip';
      $pf = storage_path('app/public/'.$filename);
      Log::info($pf);

      if ($zip->open($pf, ZipArchive::CREATE) === TRUE)
      {
          $files = $league->filenames;
          Log::debug(print_r($files,true));

          foreach ($files as $f) {
              $f =  storage_path('app/'.$f);
              $check = $zip->addFile($f, basename($f));
          }

          $zip->close();
        //  Storage::move(public_path($fileName), 'public/'.$fileName);

          return Storage::download('public/'.$filename);
      }
    } else {
      return abort(404);
    }
  }
}
