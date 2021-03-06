<?php

namespace App\Listeners;
use Illuminate\Auth\Events\Authenticated;

class LogAuthenticated
{
  /**
   * Handle the event.
   *
   * @param  \Illuminate\Auth\Events\Registered  $event
   * @return void
   */
  public function handle(Authenticated $event)
  {
      if ( ! session()->has('cur_region') ){
        session(['cur_region' => $event->user->region]);
      }
  }
}
