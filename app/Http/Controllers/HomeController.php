<?php

namespace App\Http\Controllers;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\Message;
use App\Models\User;
use App\Enums\MessageScopeType;
use App\Enums\MessageType;
use Carbon\Carbon;

class HomeController extends Controller
{
  public function approval()
  {
      return view('auth/approval');
  }

  public function home()
  {
    $today = Carbon::today()->toDateString();
    Log::debug($today);

    $user = Auth::user();

    $msglist = array();
    $vf = null;
    $mi = array();

    foreach ($user->unreadNotifications as $m){
      $valid_from = Carbon::parse($m->created_at)->locale( app()->getLocale() )->isoFormat('L');
      if ($vf != $valid_from){
        $msglist[] = $mi;
        $mi = array();
        $mi['valid_from'] = $valid_from;
        $vf = $valid_from;
      }

      $md = array();
      $md['body'] = $m->data['greeting'].', '.$m->data['lines'].', '.$m->data['salutation'];
      $md['subject'] = $m->data['subject'];
      $md['author'] = User::find($m->notifiable_id )->name;
      $md['type'] = null;
      $mi['items'][] = $md;

    }

    $msglist[] = $mi;
    array_shift($msglist);

    Log::debug(print_r($msglist,true));




    return view('home', ['msglist' => $msglist]);
  }
}
