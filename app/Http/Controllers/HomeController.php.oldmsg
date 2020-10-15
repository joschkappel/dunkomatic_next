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

    $msgs = Message::whereDate('valid_from','<=',$today)
                   ->whereDate('valid_to','>=',$today)
                   ->whereHas('destinations', function ( Builder $query){
                      $query->where('region',Auth::user()->region)
                            ->where('scope',MessageScopeType::fromValue(MessageScopeType::User)->value);
                      })
                   ->orderby('valid_from')
                   ->get();

    Log::debug(print_r($msgs,true));
    $msglist = array();
    $vf = null;
    $mi = array();

    foreach ($msgs as $m){
      if ($vf != $m->valid_from){
        $msglist[] = $mi;
        $mi = array();
        $mi['valid_from'] = $m->valid_from;
        $vf = $m->valid_from;
      }

      $md = array();
      $md['body'] = $m->body;
      $md['author'] = User::find($m->author)->name;
      foreach ( $m['destinations'] as $d){
        if ($d['scope'] ==  MessageScopeType::fromValue(MessageScopeType::User)->value ) {
          $md['type'] = $d['type'];
        } else {
          $md['type'] = null;
        }
      }

      $mi['items'][] = $md;

    }
    $msglist[] = $mi;
    array_shift($msglist);

    Log::debug(print_r($msglist,true));




    return view('home', ['msglist' => $msglist]);
  }
}
