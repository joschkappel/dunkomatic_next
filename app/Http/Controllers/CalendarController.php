<?php

namespace App\Http\Controllers;

use App\Models\League;
use App\Helpers\CalendarComposer;
use App\Models\Club;
use Illuminate\Support\Facades\Log;


class CalendarController extends Controller
{
    /**
     * Donload league games iCal
     *
     * @return \Illuminate\Http\Response
     */
    public function cal_league($language, League $league)
    {
      $calendar = CalendarComposer::createLeagueCalendar($league);
      Log::info('preparing iCAL data for league games', ['league-id'=>$league->id]);

      return response($calendar->get(), 200, [
         'Content-Type' => 'text/calendar',
         'Content-Disposition' => 'attachment; filename="'.$league->region.'-'.$league->shortname.'.ics"',
         'charset' => 'utf-8',
      ]);
    }

    /**
     * Donload club games iCal
     *
     * @return \Illuminate\Http\Response
     */
    public function cal_club($language, Club $club)
    {
      $calendar = CalendarComposer::createClubCalendar($club);
      Log::info('preparing iCAL data for club games', ['club-id'=>$club->id]);

      return response($calendar->get(), 200, [
         'Content-Type' => 'text/calendar',
         'Content-Disposition' => 'attachment; filename="'.$club->region.'-'.$club->shortname.'.ics"',
         'charset' => 'utf-8',
      ]);
    }


}
