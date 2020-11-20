<?php

namespace App\Helpers;

use App\Models\League;
use App\Models\Club;
use App\Models\Game;
use App\Helpers\CalendarComposer;

use Spatie\IcalendarGenerator\Components\Calendar;
use Spatie\IcalendarGenerator\Components\Event;

use Carbon\Carbon;

class CalendarComposer
{

  public static function createLeagueCalendar(League $league): ?Calendar
  {

    // get games
    $games =  Game::where('league_id',$league->id)
                  ->whereNotNull('game_date')
                  ->whereNotNull('game_time')
                  ->whereNotNull('team_home')
                  ->whereNotNull('team_guest')
                  ->with('league','gym')
                  ->orderBy('game_date','asc')
                  ->orderBy('game_time','asc')
                  ->orderBy('game_no','asc')
                  ->get();

    if ($games->count() > 0){
      $calendar = Calendar::create()
                  ->name($league->shortname)
                  ->description('HBV-DA '.$league->name.' Spiele Saison 20/21');

      $eventlist = array();
      // add games as calendar events
      foreach ($games as $g){
        $eventlist[] = Event::create()
                        ->name($g->league->shortname.': '.$g->team_home.' - '.$g->team_guest)
                        ->description('game desc / referee ?')
                        ->uniqueIdentifier($g->league->shortname.$g->game_no)
                        ->createdAt(Carbon::now())
                        ->startsAt(Carbon::parse(Carbon::parse($g->game_date)->isoFormat('L').' '.Carbon::parse($g->game_time)->isoFormat('LT')))
                        ->endsAt(Carbon::parse(Carbon::parse($g->game_date)->isoFormat('L').' '.Carbon::parse($g->game_time)->isoFormat('LT'))->addHours(2))
                        ->address($g->gym->street.', '.$g->gym->zip.' '.$g->gym->city)
                        ->addressName($g->gym->name)
                        ->organizer('dunkomatic@gmail.com', 'dunkOmatic')
                        ->alertMinutesBefore(120, $g->league->shortname.': '.$g->team_home.' - '.$g->team_guest.' beginnt in 2 Stunden');

      }

      $calendar = $calendar->event($eventlist);

      return $calendar;

    } else {
      return null;
    }

  }

  public static function createClubCalendar(Club $club): ?Calendar
  {
    // get games
    $club_id = $club->id;
    $games =  Game::where( function ($query) use ($club_id) {
                      $query->where('club_id_home',$club_id)
                            ->orWhere('club_id_guest', $club_id);
                       })
                  ->whereNotNull('game_date')
                  ->whereNotNull('game_time')
                  ->whereNotNull('team_home')
                  ->whereNotNull('team_guest')
                  ->with('league','gym')
                  ->orderBy('game_date','asc')
                  ->orderBy('game_time','asc')
                  ->orderBy('game_no','asc')
                  ->get();

    if ($games->count() > 0){
      $calendar = Calendar::create()
                  ->name($club->shortname)
                  ->description('HBV-DA '.$club->name.' Spiele Saison 20/21');

      $eventlist = array();
      // add games as calendar events
      foreach ($games as $g){
        $eventlist[] = Event::create()
                        ->name($g->league->shortname.': '.$g->team_home.' - '.$g->team_guest)
                        ->description('game desc / referee ?')
                        ->uniqueIdentifier($g->league->shortname.$g->game_no)
                        ->createdAt(Carbon::now())
                        ->startsAt(Carbon::parse(Carbon::parse($g->game_date)->isoFormat('L').' '.Carbon::parse($g->game_time)->isoFormat('LT')))
                        ->endsAt(Carbon::parse(Carbon::parse($g->game_date)->isoFormat('L').' '.Carbon::parse($g->game_time)->isoFormat('LT'))->addHours(2))
                        ->address($g->gym->street.', '.$g->gym->zip.' '.$g->gym->city)
                        ->addressName($g->gym->name)
                        ->organizer('dunkomatic@gmail.com', 'dunkOmatic')
                        ->alertMinutesBefore(120, $g->league->shortname.': '.$g->team_home.' - '.$g->team_guest.' beginnt in 2 Stunden');

      }

      $calendar = $calendar->event($eventlist);

      return $calendar;
    } else {
      return null;
    }
  }

  public static function createClubHomeCalendar(Club $club): ?Calendar
  {
    // get games
    $games =  Game::where('club_id_home',$club->id)
                  ->whereNotNull('game_date')
                  ->whereNotNull('game_time')
                  ->whereNotNull('team_home')
                  ->whereNotNull('team_guest')
                  ->with('league','gym')
                  ->orderBy('game_date','asc')
                  ->orderBy('game_time','asc')
                  ->orderBy('game_no','asc')
                  ->get();

    if ($games->count() > 0){
      $calendar = Calendar::create()
                  ->name($club->shortname)
                  ->description('HBV-DA '.$club->name.' Spiele Saison 20/21');

      $eventlist = array();
      // add games as calendar events
      foreach ($games as $g){
        $eventlist[] = Event::create()
                        ->name($g->league->shortname.': '.$g->team_home.' - '.$g->team_guest)
                        ->description('game desc / referee ?')
                        ->uniqueIdentifier($g->league->shortname.$g->game_no)
                        ->createdAt(Carbon::now())
                        ->startsAt(Carbon::parse(Carbon::parse($g->game_date)->isoFormat('L').' '.Carbon::parse($g->game_time)->isoFormat('LT')))
                        ->endsAt(Carbon::parse(Carbon::parse($g->game_date)->isoFormat('L').' '.Carbon::parse($g->game_time)->isoFormat('LT'))->addHours(2))
                        ->address($g->gym->street.', '.$g->gym->zip.' '.$g->gym->city)
                        ->addressName($g->gym->name)
                        ->organizer('dunkomatic@gmail.com', 'dunkOmatic')
                        ->alertMinutesBefore(120, $g->league->shortname.': '.$g->team_home.' - '.$g->team_guest.' beginnt in 2 Stunden');

      }

      $calendar = $calendar->event($eventlist);

      return $calendar;
    } else {
      return null;
    }
  }

  public static function createClubLeagueCalendar(Club $club, League $league): ?Calendar
  {
    // get games
    $club_id = $club->id;

    $games =  Game::where('league_id', $league->id)
                   ->where( function ($query) use ($club_id) {
                        $query->where('club_id_home',$club_id)
                              ->orWhere('club_id_guest', $club_id);
                         })
                  ->whereNotNull('game_date')
                  ->whereNotNull('game_time')
                  ->whereNotNull('team_home')
                  ->whereNotNull('team_guest')
                  ->with('league','gym')
                  ->orderBy('game_date','asc')
                  ->orderBy('game_time','asc')
                  ->orderBy('game_no','asc')
                  ->get();

    if ($games->count() > 0){
      $calendar = Calendar::create()
                  ->name($club->shortname)
                  ->description('HBV-DA '.$club->name.' Runde '.$league->name.' Spiele Saison 20/21');

      $eventlist = array();
      // add games as calendar events
      foreach ($games as $g){
        $eventlist[] = Event::create()
                        ->name($g->league->shortname.': '.$g->team_home.' - '.$g->team_guest)
                        ->description('game desc / referee ?')
                        ->uniqueIdentifier($g->league->shortname.$g->game_no)
                        ->createdAt(Carbon::now())
                        ->startsAt(Carbon::parse(Carbon::parse($g->game_date)->isoFormat('L').' '.Carbon::parse($g->game_time)->isoFormat('LT')))
                        ->endsAt(Carbon::parse(Carbon::parse($g->game_date)->isoFormat('L').' '.Carbon::parse($g->game_time)->isoFormat('LT'))->addHours(2))
                        ->address($g->gym->street.', '.$g->gym->zip.' '.$g->gym->city)
                        ->addressName($g->gym->name)
                        ->organizer('dunkomatic@gmail.com', 'dunkOmatic')
                        ->alertMinutesBefore(120, $g->league->shortname.': '.$g->team_home.' - '.$g->team_guest.' beginnt in 2 Stunden');

      }

      $calendar = $calendar->event($eventlist);

      return $calendar;
    } else {
      return null;
    }
  }

  public static function createClubRefereeCalendar(Club $club): ?Calendar
  {
    // get games
    $club_id = $club->id;
    $shortname = $club->shortname;
    $games =  Game::where( function ($query) use ($club_id) {
                   $query->where('club_id_home',$club_id)
                         ->where('referee_1','****');
                       })
                  ->orWhere( function ($query) use ($shortname) {
                   $query->where('referee_1',$shortname)
                         ->orWhere('referee_2',$shortname);
                       })
                  ->whereNotNull('game_date')
                  ->whereNotNull('game_time')
                  ->whereNotNull('team_home')
                  ->whereNotNull('team_guest')
                  ->with('league','gym')
                  ->orderBy('game_date','asc')
                  ->orderBy('game_time','asc')
                  ->orderBy('game_no','asc')
                  ->get();

    if ($games->count() > 0){
      $calendar = Calendar::create()
                  ->name($club->shortname)
                  ->description('HBV-DA '.$club->name.' Spiele Saison 20/21');

      $eventlist = array();
      // add games as calendar events
      foreach ($games as $g){
        $eventlist[] = Event::create()
                        ->name($g->league->shortname.': '.$g->team_home.' - '.$g->team_guest)
                        ->description('game desc / referee ?')
                        ->uniqueIdentifier($g->league->shortname.$g->game_no)
                        ->createdAt(Carbon::now())
                        ->startsAt(Carbon::parse(Carbon::parse($g->game_date)->isoFormat('L').' '.Carbon::parse($g->game_time)->isoFormat('LT')))
                        ->endsAt(Carbon::parse(Carbon::parse($g->game_date)->isoFormat('L').' '.Carbon::parse($g->game_time)->isoFormat('LT'))->addHours(2))
                        ->address($g->gym->street.', '.$g->gym->zip.' '.$g->gym->city)
                        ->addressName($g->gym->name)
                        ->organizer('dunkomatic@gmail.com', 'dunkOmatic')
                        ->alertMinutesBefore(120, $g->league->shortname.': '.$g->team_home.' - '.$g->team_guest.' beginnt in 2 Stunden');

      }

      $calendar = $calendar->event($eventlist);

      return $calendar;
    } else {
      return null;
    }
  }

}
