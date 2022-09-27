<?php

namespace App\Helpers;

use App\Models\Club;
use App\Models\Game;
use App\Models\League;
use App\Models\Region;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Spatie\IcalendarGenerator\Components\Calendar;
use Spatie\IcalendarGenerator\Components\Event;

class CalendarComposer
{
    public static function createRegionCalendar(Region $region): ?Calendar
    {
        Log::notice('[CALENDAR EXPORT] creating calendar events.', ['region-id' => $region->id]);
        // get games
        $games = Game::where('region_id_league', $region->id)
            ->whereNotNull('game_date')
            ->whereNotNull('game_time')
            ->whereNotNull('team_id_home')
            ->whereNotNull('team_id_guest')
            ->with(['league', 'gym', 'team_home.club', 'team_guest.club'])
            ->orderBy('game_date', 'asc')
            ->orderBy('game_time', 'asc')
            ->orderBy('game_no', 'asc')
            ->get();

        if ($games->count() > 0) {
            Log::notice('[CALENDAR EXPORT] games found for region.', ['region-id' => $region->id, 'count' => $games->count()]);
            $calendar = Calendar::create()
                ->name($region->code)
                ->description($region->code.' '.__('Season').' '.Setting::where('name', 'season')->first()->value);

            $eventlist = [];
            // add games as calendar events
            foreach ($games as $g) {
                $eventlist[] = Event::create()
                    ->name($g->league.': '.$g->team_home.' - '.$g->team_guest)
                    ->description($g->league()->first()->name.' '.__('game.game_no').' '.$g->game_no.' '.__('game.referee').' '.$g->referee_1)
                    ->uniqueIdentifier($g->league.$g->game_no)
                    ->createdAt(Carbon::now())
                    ->startsAt(Carbon::parse(Carbon::parse($g->game_date)->isoFormat('L').' '.Carbon::parse($g->game_time)->isoFormat('LT')))
                    ->endsAt(Carbon::parse(Carbon::parse($g->game_date)->isoFormat('L').' '.Carbon::parse($g->game_time)->isoFormat('LT'))->addHours(2))
                    ->address(($g->gym->street ?? '').', '.($g->gym->zip ?? '').' '.($g->gym->city ?? ''))
                    ->addressName($g->gym->name ?? '')
                    ->organizer(config('app.contact'), config('app.name'))
                    ->alertMinutesBefore(120, $g->league.': '.$g->team_home.' - '.$g->team_guest.' '.__('game.starts_in', ['hours' => 2]));
            }

            $calendar = $calendar->event($eventlist);

            return $calendar;
        } else {
            Log::warning('[CALENDAR EXPORT] no games found for region.', ['region-id' => $region->id]);

            return null;
        }
    }

    public static function createLeagueCalendar(League $league): ?Calendar
    {
        Log::notice('[CALENDAR EXPORT] creating calendar events.', ['league-id' => $league->id]);
        // get games
        $games = Game::where('league_id', $league->id)
            ->whereNotNull('game_date')
            ->whereNotNull('game_time')
            ->whereNotNull('team_id_home')
            ->whereNotNull('team_id_guest')
            ->with(['league', 'gym', 'team_home.club.region', 'team_guest.club.region'])
            ->orderBy('game_date', 'asc')
            ->orderBy('game_time', 'asc')
            ->orderBy('game_no', 'asc')
            ->get();

        if ($games->count() > 0) {
            Log::notice('[CALENDAR EXPORT] games found for league.', ['league-id' => $league->id, 'count' => $games->count()]);
            $calendar = Calendar::create()
                ->name($league->shortname)
                ->description($league->region->code.' '.$league->name.' '.__('Season').' '.Setting::where('name', 'season')->first()->value);

            $eventlist = [];
            // add games as calendar events
            foreach ($games as $g) {
                $eventlist[] = Event::create()
                    ->name($g->league.': '.$g->team_home.' - '.$g->team_guest)
                    ->description($league->name.' '.__('game.game_no').' '.$g->game_no.' '.__('game.referee').' '.$g->referee_1)
                    ->uniqueIdentifier($g->league.$g->game_no)
                    ->createdAt(Carbon::now())
                    ->startsAt(Carbon::parse(Carbon::parse($g->game_date)->isoFormat('L').' '.Carbon::parse($g->game_time)->isoFormat('LT')))
                    ->endsAt(Carbon::parse(Carbon::parse($g->game_date)->isoFormat('L').' '.Carbon::parse($g->game_time)->isoFormat('LT'))->addHours(2))
                    ->address(($g->gym->street ?? '').', '.($g->gym->zip ?? '').' '.($g->gym->city ?? ''))
                    ->addressName($g->gym->name ?? '')
                    ->organizer(config('app.contact'), config('app.name'))
                    ->alertMinutesBefore(120, $g->league.': '.$g->team_home.' - '.$g->team_guest.' '.__('game.starts_in', ['hours' => 2]));
            }

            $calendar = $calendar->event($eventlist);

            return $calendar;
        } else {
            Log::warning('[CALENDAR EXPORT] no games found for league.', ['league-id' => $league->id]);

            return null;
        }
    }

    public static function createClubCalendar(Club $club): ?Calendar
    {
        Log::notice('[CALENDAR EXPORT] creating calendar events.', ['club-id' => $club->id]);
        // get games
        $club_id = $club->id;
        $games = Game::where(function ($query) use ($club_id) {
            $query->where('club_id_home', $club_id)
                ->orWhere('club_id_guest', $club_id);
        })
            ->whereNotNull('game_date')
            ->whereNotNull('game_time')
            ->whereNotNull('team_id_home')
            ->whereNotNull('team_id_guest')
            ->with(['league', 'gym', 'team_home.club.region', 'team_guest.club.region'])
            ->orderBy('game_date', 'asc')
            ->orderBy('game_time', 'asc')
            ->orderBy('game_no', 'asc')
            ->get();

        if ($games->count() > 0) {
            Log::notice('[CALENDAR EXPORT] games found for club.', ['club-id' => $club->id, 'count' => $games->count()]);

            $calendar = Calendar::create()
                ->name($club->shortname)
                ->description($club->region->code.' '.$club->name.' '.__('Season').' '.Setting::where('name', 'season')->first()->value);

            $eventlist = [];
            // add games as calendar events
            foreach ($games as $g) {
                $eventlist[] = Event::create()
                    ->name($g->league.': '.$g->team_home.' - '.$g->team_guest)
                    ->description($g->league()->first()->name.' '.__('game.game_no').' '.$g->game_no.' '.__('game.referee').' '.$g->referee_1)
                    ->uniqueIdentifier($g->league.$g->game_no)
                    ->createdAt(Carbon::now())
                    ->startsAt(Carbon::parse(Carbon::parse($g->game_date)->isoFormat('L').' '.Carbon::parse($g->game_time)->isoFormat('LT')))
                    ->endsAt(Carbon::parse(Carbon::parse($g->game_date)->isoFormat('L').' '.Carbon::parse($g->game_time)->isoFormat('LT'))->addHours(2))
                    ->address(($g->gym->street ?? '').', '.($g->gym->zip ?? '').' '.($g->gym->city ?? ''))
                    ->addressName($g->gym->name ?? '')
                    ->organizer(config('app.contact'), config('app.name'))
                    ->alertMinutesBefore(120, $g->league.': '.$g->team_home.' - '.$g->team_guest.' '.__('game.starts_in', ['hours' => 2]));
            }

            $calendar = $calendar->event($eventlist);

            return $calendar;
        } else {
            Log::warning('[CALENDAR EXPORT] no games found for club.', ['club-id' => $club->id]);

            return null;
        }
    }

    public static function createClubHomeCalendar(Club $club): ?Calendar
    {
        Log::notice('[CALENDAR EXPORT] creating calendar events.', ['club-id' => $club->id]);
        // get games
        $games = Game::where('club_id_home', $club->id)
            ->whereNotNull('game_date')
            ->whereNotNull('game_time')
            ->whereNotNull('team_id_home')
            ->whereNotNull('team_id_guest')
            ->with(['league', 'gym', 'team_home.club.region', 'team_guest.club.region'])
            ->orderBy('game_date', 'asc')
            ->orderBy('game_time', 'asc')
            ->orderBy('game_no', 'asc')
            ->get();

        if ($games->count() > 0) {
            Log::notice('[CALENDAR EXPORT] home games found for club.', ['club-id' => $club->id, 'count' => $games->count()]);

            $calendar = Calendar::create()
                ->name($club->shortname)
                ->description($club->region->code.' '.$club->name.' '.__('Season').' '.Setting::where('name', 'season')->first()->value);

            $eventlist = [];
            // add games as calendar events
            foreach ($games as $g) {
                $eventlist[] = Event::create()
                    ->name($g->league.': '.$g->team_home.' - '.$g->team_guest)
                    ->description($g->league()->first()->name.' '.__('game.game_no').' '.$g->game_no.' '.__('game.referee').' '.$g->referee_1)
                    ->uniqueIdentifier($g->league.$g->game_no)
                    ->createdAt(Carbon::now())
                    ->startsAt(Carbon::parse(Carbon::parse($g->game_date)->isoFormat('L').' '.Carbon::parse($g->game_time)->isoFormat('LT')))
                    ->endsAt(Carbon::parse(Carbon::parse($g->game_date)->isoFormat('L').' '.Carbon::parse($g->game_time)->isoFormat('LT'))->addHours(2))
                    ->address(($g->gym->street ?? '').', '.($g->gym->zip ?? '').' '.($g->gym->city ?? ''))
                    ->addressName($g->gym->name ?? '')
                    ->organizer(config('app.contact'), config('app.name'))
                    ->alertMinutesBefore(120, $g->league.': '.$g->team_home.' - '.$g->team_guest.' '.__('game.starts_in', ['hours' => 2]));
            }

            $calendar = $calendar->event($eventlist);

            return $calendar;
        } else {
            Log::warning('[CALENDAR EXPORT] no home games found for club.', ['club-id' => $club->id]);

            return null;
        }
    }

    public static function createClubLeagueCalendar(Club $club, League $league): ?Calendar
    {
        Log::notice('[CALENDAR EXPORT] creating calendar events.', ['club-id' => $club->id, 'league-id' => $league->id]);
        // get games
        $club_id = $club->id;

        $games = Game::where('league_id', $league->id)
            ->where(function ($query) use ($club_id) {
                $query->where('club_id_home', $club_id)
                    ->orWhere('club_id_guest', $club_id);
            })
            ->whereNotNull('game_date')
            ->whereNotNull('game_time')
            ->whereNotNull('team_id_home')
            ->whereNotNull('team_id_guest')
            ->with(['league', 'gym', 'team_home.club.region', 'team_guest.club.region'])
            ->orderBy('game_date', 'asc')
            ->orderBy('game_time', 'asc')
            ->orderBy('game_no', 'asc')
            ->get();

        if ($games->count() > 0) {
            Log::notice('[CALENDAR EXPORT] league games found for club.', ['club-id' => $club->id, 'league-id' => $league->id, 'count' => $games->count()]);

            $calendar = Calendar::create()
                ->name($club->shortname)
                ->description($club->region->code.' '.$club->name.' '.$league->name.' '.__('Season').' '.Setting::where('name', 'season')->first()->value);

            $eventlist = [];
            // add games as calendar events
            foreach ($games as $g) {
                $eventlist[] = Event::create()
                    ->name($g->league.': '.$g->team_home.' - '.$g->team_guest)
                    ->description($g->league()->first()->name.' '.__('game.game_no').' '.$g->game_no.' '.__('game.referee').' '.$g->referee_1)
                    ->uniqueIdentifier($g->league.$g->game_no)
                    ->createdAt(Carbon::now())
                    ->startsAt(Carbon::parse(Carbon::parse($g->game_date)->isoFormat('L').' '.Carbon::parse($g->game_time)->isoFormat('LT')))
                    ->endsAt(Carbon::parse(Carbon::parse($g->game_date)->isoFormat('L').' '.Carbon::parse($g->game_time)->isoFormat('LT'))->addHours(2))
                    ->address(($g->gym->street ?? '').', '.($g->gym->zip ?? '').' '.($g->gym->city ?? ''))
                    ->addressName($g->gym->name ?? '')
                    ->organizer(config('app.contact'), config('app.name'))
                    ->alertMinutesBefore(120, $g->league.': '.$g->team_home.' - '.$g->team_guest.' '.__('game.starts_in', ['hours' => 2]));
            }

            $calendar = $calendar->event($eventlist);

            return $calendar;
        } else {
            Log::warning('[CALENDAR EXPORT] no games found for club and league.', ['club-id' => $club->id, 'league-id' => $league->id]);

            return null;
        }
    }

    public static function createClubRefereeCalendar(Club $club): ?Calendar
    {
        Log::notice('[CALENDAR EXPORT] creating calendar events.', ['club-id' => $club->id]);
        // get games
        $club_id = $club->id;
        $shortname = $club->shortname;
        $games = Game::where(function ($query) use ($club_id) {
            $query->where('club_id_home', $club_id)
                ->where('referee_1', '****');
        })
            ->orWhere(function ($query) use ($shortname) {
                $query->where('referee_1', $shortname)
                    ->orWhere('referee_2', $shortname);
            })
            ->whereNotNull('game_date')
            ->whereNotNull('game_time')
            ->whereNotNull('team_id_home')
            ->whereNotNull('team_id_guest')
            ->with(['league', 'gym', 'team_home.club.region', 'team_guest.club.region'])
            ->orderBy('game_date', 'asc')
            ->orderBy('game_time', 'asc')
            ->orderBy('game_no', 'asc')
            ->get();

        if ($games->count() > 0) {
            Log::notice('[CALENDAR EXPORT] referee games found for club.', ['club-id' => $club->id, 'count' => $games->count()]);

            $calendar = Calendar::create()
                ->name($club->shortname)
                ->description($club->region->code.' '.$club->name.' '.__('Season').' '.Setting::where('name', 'season')->first()->value);

            $eventlist = [];
            // add games as calendar events
            foreach ($games as $g) {
                $eventlist[] = Event::create()
                    ->name($g->league.': '.$g->team_home.' - '.$g->team_guest)
                    ->description($g->league()->first()->name.' '.__('game.game_no').' '.$g->game_no.' '.__('game.referee').' '.$g->referee_1)
                    ->uniqueIdentifier($g->league.$g->game_no)
                    ->createdAt(Carbon::now())
                    ->startsAt(Carbon::parse(Carbon::parse($g->game_date)->isoFormat('L').' '.Carbon::parse($g->game_time)->isoFormat('LT')))
                    ->endsAt(Carbon::parse(Carbon::parse($g->game_date)->isoFormat('L').' '.Carbon::parse($g->game_time)->isoFormat('LT'))->addHours(2))
                    ->address(($g->gym->street ?? '').', '.($g->gym->zip ?? '').' '.($g->gym->city ?? ''))
                    ->addressName($g->gym->name ?? '')
                    ->organizer(config('app.contact'), config('app.name'))
                    ->alertMinutesBefore(120, $g->league.': '.$g->team_home.' - '.$g->team_guest.' '.__('game.starts_in', ['hours' => 2]));
            }

            $calendar = $calendar->event($eventlist);

            return $calendar;
        } else {
            Log::warning('[CALENDAR EXPORT] no referee games found for club.', ['club-id' => $club->id]);

            return null;
        }
    }
}
