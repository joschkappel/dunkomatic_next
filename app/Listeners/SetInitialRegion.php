<?php

namespace App\Listeners;
use Illuminate\Auth\Events\Authenticated;

class SetInitialRegion
{
  /**
   * Handle the event.
   *
   * @param  Authenticated  $event
   * @return void
   */
  public function handle(Authenticated $event)
  {
      if ( ! session()->has('cur_region') ){
        session(['cur_region' => $event->user->regions()->first()]);
      }
  }
}
