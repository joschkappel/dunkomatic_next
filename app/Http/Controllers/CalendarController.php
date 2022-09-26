<?php

namespace App\Http\Controllers;

use App\Helpers\CalendarComposer;
use App\Models\Club;
use App\Models\League;
use Illuminate\Support\Facades\Log;

class CalendarController extends Controller
{
    /**
     * Donload league games iCal
     *
     * @param  string  $language
     * @param  \App\Models\League  $league
     * @return \Illuminate\Http\Response
     */
    public function cal_league(string $language, League $league)
    {
        $calendar = CalendarComposer::createLeagueCalendar($league);
        Log::info('preparing iCAL data for league games', ['league-id' => $league->id]);

        if (isset($calendar)) {
            return response($calendar->get(), 200, [
                'Content-Type' => 'text/calendar',
                'Content-Disposition' => 'attachment; filename="'.$league->region->code.'-'.$league->shortname.'.ics"',
                'charset' => 'utf-8',
            ]);
        } else {
            return abort(404);
        }
    }

    /**
     * Donload club games iCal
     *
     * @param  string  $language
     * @param  \App\Models\Club  $club
     * @return \Illuminate\Http\Response
     */
    public function cal_club(string $language, Club $club)
    {
        $calendar = CalendarComposer::createClubCalendar($club);
        Log::info('preparing iCAL data for club games', ['club-id' => $club->id]);

        if (isset($calendar)) {
            return response($calendar->get(), 200, [
                'Content-Type' => 'text/calendar',
                'Content-Disposition' => 'attachment; filename="'.$club->region->code.'-'.$club->shortname.'.ics"',
                'charset' => 'utf-8',
            ]);
        } else {
            return abort(404);
        }
    }

    /**
     * Donload club home games iCal
     *
     * @param  string  $language
     * @param  \App\Models\Club  $club
     * @return \Illuminate\Http\Response
     */
    public function cal_club_home(string $language, Club $club)
    {
        $calendar = CalendarComposer::createClubHomeCalendar($club);
        Log::info('preparing iCAL data for club home games', ['club-id' => $club->id]);

        if (isset($calendar)) {
            return response($calendar->get(), 200, [
                'Content-Type' => 'text/calendar',
                'Content-Disposition' => 'attachment; filename="'.$club->region->code.'-'.$club->shortname.'_home.ics"',
                'charset' => 'utf-8',
            ]);
        } else {
            return abort(404);
        }
    }

    /**
     * Donload club referee games iCal
     *
     * @param  string  $language
     * @param  \App\Models\Club  $club
     * @return \Illuminate\Http\Response
     */
    public function cal_club_referee(string $language, Club $club)
    {
        $calendar = CalendarComposer::createClubRefereeCalendar($club);
        Log::info('preparing iCAL data for club referee games', ['club-id' => $club->id]);

        if (isset($calendar)) {
            return response($calendar->get(), 200, [
                'Content-Type' => 'text/calendar',
                'Content-Disposition' => 'attachment; filename="'.$club->region->code.'-'.$club->shortname.'_referee.ics"',
                'charset' => 'utf-8',
            ]);
        } else {
            return abort(404);
        }
    }
}
