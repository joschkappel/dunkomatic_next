<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ZipArchive;

use App\Models\User;

class FileDownloadController extends Controller
{
  public function get_file($season, $region, $type, $file)
  {
    return Storage::download('exports/'.$season.'/'.$region.'/'.$type.'/'.$file, $file);
  }

  public function get_archive(User $user)
  {
    Log::info('User '.$user->name.' wants to download an archive');


    if ( $user->league_filecount + $user->club_filecount > 0){
      $zip = new ZipArchive;
      $fileName = $user->region.'-reports-'.Str::slug($user->name,'-').'.zip';
      $pf = config('filesystems.disks.local.root').'/public/'.$fileName;
      //Log::info(public_path($pf));

      if ($zip->open($pf, ZipArchive::CREATE) === TRUE)
      {
          $files = $user->league_filenames;
          $files = $files->concat($user->club_filenames);
          Log::debug(print_r($files,true));

          foreach ($files as $f) {
              $f =  config('filesystems.disks.local.root').'/'.$f;
              $check = $zip->addFile($f, basename($f));
          }

          $zip->close();
        //  Storage::move(public_path($fileName), 'public/'.$fileName);

          return Storage::download('public/'.$fileName);
      }
    }


  }
}
