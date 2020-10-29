<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

use App\Models\User;

class FileDownloadController extends Controller
{
  public function get_file($season, $type, $file)
  {
    return Storage::download('exports/'.$season.'/'.$type.'/'.$file, $file);
  }

  public function get_archive(User $user)
  {
  }
}
