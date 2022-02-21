<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Events\TestEvent;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect(app()->getLocale());
})->name('start');

Route::get('healthy', function () {
    return 'OK';
});

Route::get('/fire', function () {
    event(new App\Events\TestEvent());
    return 'ok';
});

Route::get('health', HealthCheckResultsController::class);

Route::group([
    'prefix' => '{language}',
    'where' => ['language' => '[a-zA-Z]{2}'],
    'middleware' => ['set.language',
                     'set.region',
                     'set.logcontext']
], function () {

    Route::get('/', function () {
        return view('welcome');
    })->name('welcome');
    Route::get('/checkfire', function () {
        return view('broadcast.test');
    })->name('welcome');

    Auth::routes(['verify' => true, 'middleware' => 'can:register']);

    Route::get('/register_invited/{member}/{region}/{inviting_user}/{invited_by}', 'Auth\RegisterController@showRegistrationFormInvited')->name('register.invited');

    Route::middleware(['auth'])->group(function () {
        Route::get('/home', 'HomeController@home')->name('home')->middleware('auth')->middleware('verified')->middleware('approved');
        Route::get('/approval', 'HomeController@approval')->name('approval');

        Route::post('user/{user_id}/approve', 'UserController@approve')->name('admin.user.approve')->middleware('can:update-users');
        Route::get('user/{user}/edit', 'UserController@edit')->name('admin.user.edit')->middleware('auth')->middleware('can:update-users');
        Route::get('user/{user}/show', 'UserController@show')->name('admin.user.show')->middleware('can:update-profile');

        Route::get('region', 'RegionController@index')->name('region.index')->middleware('can:view-regions');
        Route::get('region/create', 'RegionController@create')->name('region.create')->middleware('can:create-regions');
        Route::get('region/{region}', 'RegionController@show')->name('region.show')->middleware('can:view-regions');
        Route::get('region/{region}/edit', 'RegionController@edit')->name('region.edit')->middleware('can:update-regions');
        Route::get('regions/dt', 'RegionController@datatable')->name('region.list.dt')->middleware('can:view-regions');
        Route::get('region/{region}/dashboard', 'RegionController@dashboard')->name('region.dashboard');
        Route::get('region/{region}/briefing', 'RegionController@briefing')->name('region.briefing')->middleware('can:view-regions');
        Route::get('region/{region}/game/upload', 'RegionGameController@upload')->name('region.upload.game');
        Route::post('region/{region}/game/ref/import', 'RegionGameController@import_referees')->name('region.import.refgame');

        Route::get('club/{club}/dashboard', 'ClubController@dashboard')->name('club.dashboard');
        Route::get('club/{club}/briefing', 'ClubController@briefing')->name('club.briefing')->middleware('can:view-clubs');
        Route::get('club/{club}/game/home', 'ClubController@list_homegame')->name('club.list.homegame')->middleware('can:view-games');
        Route::get('club/{club}', 'ClubController@show')->name('club.show')->middleware('can:view-clubs');
        Route::get('club/{club}/edit', 'ClubController@edit')->name('club.edit')->middleware('can:update-clubs');
        Route::get('club/{club}/team/dt', 'ClubController@team_dt')->name('club.team.dt');

        Route::get('club/{club}/game/upload', 'ClubGameController@upload')->name('club.upload.homegame');
        Route::post('club/{club}/game/import', 'ClubGameController@import')->name('club.import.homegame');
        Route::get('club/{club}/game/list_home', 'ClubGameController@list_home')->name('club.game.list_home')->middleware('can:view-games');
        Route::get('club/{club}/game/chart', 'ClubGameController@chart')->name('club.game.chart')->middleware('can:view-games');
        Route::get('club/{club}/team/pickchar', 'ClubTeamController@pickchar')->name('club.team.pickchar')->middleware('can:update-teams');
        Route::get('club/{club}/league_char_dt', 'ClubTeamController@league_char_dt')->name('club.league_char.dt')->middleware('can:view-teams');

        Route::get('audit/{audit}', 'AuditController@show')->name('audit.show');

        Route::group(['prefix' => 'region/{region}'], function () {
            Route::get('audit', 'AuditController@index')->name('audit.index');
            Route::get('audits/dt', 'AuditController@datatable')->name('audits.dt');

            Route::get('club', 'ClubController@index')->name('club.index')->middleware('can:view-clubs');
            Route::get('club/create', 'ClubController@create')->name('club.create')->middleware('can:create-clubs');
            Route::get('league/list', 'LeagueController@list')->name('league.list')->middleware('can:view-leagues');
            Route::get('league', 'LeagueController@index')->name('league.index')->middleware('can:view-leagues');
            Route::get('league/create', 'LeagueController@create')->name('league.create')->middleware('can:create-leagues');
            Route::get('league/list_mgmt', 'LeagueController@list_mgmt')->name('league.list_mgmt');
            Route::get('league/manage', 'LeagueController@index_mgmt')->name('league.index_mgmt');
            Route::get('schedule', 'ScheduleController@index')->name('schedule.index')->middleware('can:view-schedules');
            Route::get('schedule/create', 'ScheduleController@create')->name('schedule.create')->middleware('can:create-schedules');
            Route::get('schedule/compare/dt', 'ScheduleController@compare_datatable')->name('schedule.compare.dt')->middleware('can:view-schedules');
            Route::get('schedule/compare', 'ScheduleController@compare')->name('schedule.compare')->middleware('can:view-schedules');


            Route::get('user/new', 'UserController@index_new')->name('admin.user.index.new')->middleware('can:update-users');
            Route::get('user', 'UserController@index')->name('admin.user.index')->middleware('auth')->middleware('can:view-users');
            Route::get('user/dt', 'UserController@datatable')->name('admin.user.dt')->middleware('auth')->middleware('can:view-users');

            Route::get('user/{user}/message', 'MessageController@index')->name('message.index');
            Route::get('user/{user}/message/create', 'MessageController@create')->name('message.create');
            Route::get('user/{user}/message/dt', 'MessageController@datatable_user')->name('message.user.dt');

            Route::get('member', 'MemberController@index')->name('member.index')->middleware('can:view-members');
            Route::get('game', 'GameController@index')->name('game.index')->middleware('can:view-games');
            Route::get('game/datatable', 'GameController@datatable')->name('game.datatable')->middleware('can:view-games');

            Route::get('address/role/{role}', 'AddressController@index_byrole')->name('address.index_byrole')->middleware('can:view-members');
            Route::get('address/role/{role}/dt', 'AddressController@index_byrole_dt')->name('address.index_byrole.dt')->middleware('can:view-members');
        });

        Route::resource('club.gym', 'ClubGymController')->shallow()->except('store', 'update', 'destroy', 'show');

        Route::get('league/{league}/game/upload', 'LeagueGameController@upload')->name('league.upload.game');
        Route::post('league/{league}/game/import', 'LeagueGameController@import')->name('league.import.game');
        Route::get('league/{league}/dashboard', 'LeagueController@dashboard')->name('league.dashboard');
        Route::get('league/{league}/briefing', 'LeagueController@briefing')->name('league.briefing')->middleware('can:view-leagues');
        Route::get('league/{league}', 'LeagueController@show')->name('league.show')->middleware('can:view-leagues');
        Route::get('league/{league}/edit', 'LeagueController@edit')->name('league.edit')->middleware('can:update-leagues');
        Route::get('league/{league}/team/dt', 'LeagueController@team_dt')->name('league.team.dt');

        Route::get('league/{league}/game/dt', 'LeagueGameController@datatable')->name('league.game.dt');
        Route::resource('league.game', 'LeagueGameController')->shallow()->only(['index', 'create', 'edit']);

        Route::get('member/{member}/show', 'MemberController@show')->name('member.show')->middleware('can:view-members');
        Route::get('member/{member}', 'MemberController@edit')->name('member.edit')->middleware('can:update-members');

        Route::get('membership/club/{club}/member', 'ClubMembershipController@create')->name('membership.club.create');
        Route::get('membership/league/{league}/member', 'LeagueMembershipController@create')->name('membership.league.create');
        Route::get('membership/region/{region}/member', 'RegionMembershipController@create')->name('membership.region.create');
        Route::resource('membership', 'MembershipController')->only(['show']);

        Route::get('scheme/index', 'LeagueSizeSchemeController@index')->name('scheme.index');

        Route::get('team/league/plan/{club}', 'TeamController@plan_leagues')->name('team.plan-leagues');
        Route::post('team/league/plan/pivot', 'TeamController@list_pivot')->name('team.list-piv');
        Route::post('team/league/plan/chart', 'TeamController@list_chart')->name('team.list-chart');
        Route::post('team/league/plan/propose', 'TeamController@propose_combination')->name('team.propose');

        Route::resource('club.team', 'ClubTeamController')->shallow()->only('index', 'create', 'edit');

        Route::get('schedule_event/calendar', function () {
            return view('schedule/scheduleevent_cal');
        })->name('schedule_event.cal')->middleware('can:view-schedules');
        Route::get('schedule/{schedule}', 'ScheduleController@show')->name('schedule.show')->middleware('can:view-schedules');
        Route::get('schedule/{schedule}/edit', 'ScheduleController@edit')->name('schedule.edit')->middleware('can:update-schedules');

        Route::get('message/{message}/edit', 'MessageController@edit')->name('message.edit');
        Route::post('message/{message}/send', 'MessageController@send')->name('message.send');
        Route::post('message/{message}/copy', 'MessageController@copy')->name('message.copy');

        Route::get('calendar/league/{league}', 'CalendarController@cal_league')->name('cal.league')->middleware('can:view-games');
        Route::get('calendar/club/{club}', 'CalendarController@cal_club')->name('cal.club')->middleware('can:view-games');
        Route::get('calendar/club/{club}/home', 'CalendarController@cal_club_home')->name('cal.club.home')->middleware('can:view-games');
        Route::get('calendar/club/{club}/referee', 'CalendarController@cal_club_referee')->name('cal.club.referee')->middleware('can:view-games');
    });
});

Route::get('region/admin/sb', 'RegionController@admin_sb')->name('region.admin.sb')->middleware('set.logcontext');

Route::middleware(['auth',
                   'set.region',
                   'set.logcontext'])->group(function () {
    // APIs , no locale or language required !
    Route::redirect('home', '/de/home');

    Route::post('region', 'RegionController@store')->name('region.store')->middleware('can:create-regions');
    Route::put('region/{region}', 'RegionController@update')->name('region.update')->middleware('can:update-regions');
    Route::delete('region/{region}', 'RegionController@destroy')->name('region.destroy')->middleware('can:create-regions');
    Route::get('region/hq/sb', 'RegionController@hq_sb')->name('region.hq.sb');

    Route::delete('user/{user}', 'UserController@destroy')->name('admin.user.destroy')->middleware('can:update-users');
    Route::post('user/{user}/block', 'UserController@block')->name('admin.user.block')->middleware('can:update-users');
    Route::put('user/{user}', 'UserController@update')->name('admin.user.update')->middleware('can:update-users');
    Route::put('user/{user}/allowance', 'UserController@allowance')->name('admin.user.allowance')->middleware('can:update-users');

    Route::put('member/{member}', 'MemberController@update')->name('member.update')->middleware('can:update-members');
    Route::post('member', 'MemberController@store')->name('member.store')->middleware('can:create-members');
    Route::get('member/{member}/invite', 'MemberController@invite')->name('member.invite')->middleware('can:update-members');
    Route::delete('member/{member}', 'MemberController@destroy')->name('member.destroy')->middleware('can:create-members');

    Route::put('region/{region}/details', 'RegionController@update_details')->name('region.update_details')->middleware('can:update-regions');

    Route::put('club/{club}', 'ClubController@update')->name('club.update')->middleware('can:update-clubs');
    Route::delete('club/{club}', 'ClubController@destroy')->name('club.destroy')->middleware('can:create-clubs');
    Route::get('club/{club}/league/sb', 'ClubController@sb_league')->name('club.sb.league');

    Route::get('club/{club}/game/chart_home', 'ClubGameController@chart_home')->name('club.game.chart_home');
    Route::get('gym/{gym}/list', 'ClubGymController@sb_gym')->name('gym.sb.gym');
    Route::get('club/{club}/list/gym', 'ClubGymController@sb_club')->name('gym.sb.club');
    Route::get('team/{team}/list/gym', 'ClubGymController@sb_team')->name('gym.sb.team');
    Route::resource('club.gym', 'ClubGymController')->shallow()->only('store', 'update', 'destroy');

    Route::group(['prefix' => 'region/{region}'], function () {
        Route::get('club/list', 'ClubController@list')->name('club.list')->middleware('can:view-clubs');
        Route::post('club', 'ClubController@store')->name('club.store')->middleware('can:create-clubs');
        Route::post('league', 'LeagueController@store')->name('league.store')->middleware('can:create-leagues');
        Route::get('schedule/list', 'ScheduleController@list')->name('schedule.list')->middleware('can:view-schedules');

        Route::get('club/sb', 'ClubController@sb_region')->name('club.sb.region');
        Route::get('league/sb', 'LeagueController@sb_region')->name('league.sb.region');
        Route::get('schedule/sb', 'ScheduleController@sb_region')->name('schedule.sb.region');
        Route::get('member/sb', 'MemberController@sb_region')->name('member.sb.region');

        Route::post('user/{user}/message', 'MessageController@store')->name('message.store');

        Route::get('member/datatable', 'MemberController@datatable')->name('member.datatable')->middleware('can:view-members');

        Route::get('league/team_register/dt', 'LeagueController@team_register_dt')->name('league.team_register.dt');
        Route::get('schedule/size/{size}/sb', 'ScheduleController@sb_region_size')->name('schedule.sb.region_size');
        Route::get('schedule_event/list-cal', 'ScheduleEventController@list_cal')->name('schedule_event.list-cal')->middleware('can:view-schedules');

        Route::get('league/team', 'RegionController@league_team_chart')->name('region.league.team.chart')->middleware('can:view-leagues');
        Route::get('league/state', 'RegionController@league_state_chart')->name('region.league.state.chart')->middleware('can:view-leagues');
        Route::get('league/socio', 'RegionController@league_socio_chart')->name('region.league.socio.chart')->middleware('can:view-leagues');
        Route::get('club/team', 'RegionController@club_team_chart')->name('region.club.team.chart')->middleware('can:view-clubs');
        Route::get('club/member', 'RegionController@club_member_chart')->name('region.club.member.chart')->middleware('can:view-clubs');
        Route::get('game/noreferee', 'RegionController@game_noreferee_chart')->name('region.game.noreferee.chart')->middleware('can:view-games');

    });

    Route::post('league/{league}/club', 'LeagueTeamController@assign_clubs')->name('league.assign-clubs')->middleware('can:update-leagues');
    Route::delete('league/{league}/club/{club}', 'LeagueTeamController@deassign_club')->name('league.deassign-club')->middleware('can:update-leagues');
    Route::put('league/{league}/team/{team}', 'LeagueTeamController@league_register_team')->name('league.register.team')->middleware('can:update-teams');
    Route::delete('league/{league}/team/{team}', 'LeagueTeamController@league_unregister_team')->name('league.unregister.team')->middleware('can:update-teams');
    Route::put('team/{team}/league', 'LeagueTeamController@team_register_league')->name('team.register.league')->middleware('can:update-teams');
    Route::post('league/{league}/team', 'LeagueTeamController@inject')->name('league.team.inject')->middleware('can:update-teams');
    Route::delete('league/{league}/team', 'LeagueTeamController@withdraw')->name('league.team.withdraw')->middleware('can:update-teams');
    Route::post('league/{league}/pickchar', 'LeagueTeamController@pick_char')->name('league.team.pickchar')->middleware('can:update-teams');
    Route::post('league/{league}/releasechar', 'LeagueTeamController@release_char')->name('league.team.releasechar')->middleware('can:update-teams');

    Route::get('league/{league}/freechar/sb', 'LeagueController@sb_freechars')->name('league.sb_freechar');
    Route::get('league/{league}/club/sb', 'LeagueController@sb_club')->name('league.sb.club');
    Route::post('league/{league}/state', 'LeagueStateController@change_state')->name('league.state.change')->middleware('can:update-leagues');
    Route::put('league/{league}', 'LeagueController@update')->name('league.update')->middleware('can:update-leagues');
    Route::delete('league/{league}', 'LeagueController@destroy')->name('league.destroy')->middleware('can:create-leagues');

    Route::post('membership/club/{club}/member/{member}', 'ClubMembershipController@add')->name('membership.club.add');
    Route::delete('membership/club/{club}/member/{member}', 'ClubMembershipController@destroy')->name('membership.club.destroy');
    Route::post('membership/league/{league}/member/{member}', 'LeagueMembershipController@add')->name('membership.league.add');
    Route::delete('membership/league/{league}/member/{member}', 'LeagueMembershipController@destroy')->name('membership.league.destroy');
    Route::post('membership/region/{region}/member/{member}', 'RegionMembershipController@add')->name('membership.region.add');
    Route::delete('membership/region/{region}/member/{member}', 'RegionMembershipController@destroy')->name('membership.region.destroy');
    Route::put('membership/{membership}', 'MembershipController@update')->name('membership.update');
    Route::delete('membership/{membership}', 'MembershipController@destroy')->name('membership.destroy');
    Route::post('membership', 'MembershipController@store')->name('membership.store');

    Route::delete('league/{league}/game', 'LeagueGameController@destroy_game')->name('league.game.destroy');
    Route::get('league/{league}/game/{game_no}', 'LeagueGameController@show_by_number')->name('league.game.show_bynumber');
    Route::delete('league/{league}/game/noshow', 'LeagueGameController@destroy_noshow_game')->name('league.game.destroy_noshow');
    Route::put('game/{game}/home', 'LeagueGameController@update_home')->name('game.update_home');
    Route::resource('league.game', 'LeagueGameController')->shallow()->except(['index', 'create', 'edit']);

    Route::get('league/{league}/team/sb', 'TeamController@sb_league')->name('league.team.sb');
    Route::get('team/league/{league}/free/sb', 'TeamController@sb_freeteam')->name('team.free.sb');
    Route::post('team/league/plan', 'TeamController@store_plan')->name('team.store-plan')->middleware('can:update-teams');

    Route::resource('club.team', 'ClubTeamController')->shallow()->except('index', 'create', 'edit');

    Route::post('role/index', 'RoleController@index')->name('role.index');

    Route::get('scheme/{size}/list_piv', 'LeagueSizeSchemeController@list_piv')->name('scheme.list_piv');
    Route::get('size/index', 'LeagueSizeController@index')->name('size.index');

    Route::post('schedule_event/list-piv', 'ScheduleEventController@list_piv')->name('schedule_event.list-piv');
    Route::get('schedule_event/{schedule}/list', 'ScheduleEventController@list')->name('schedule_event.list');
    Route::get('schedule_event/{schedule}/dt', 'ScheduleEventController@datatable')->name('schedule_event.dt');
    Route::post('schedule_event/{schedule}/shift', 'ScheduleEventController@shift')->name('schedule_event.shift');
    Route::post('schedule_event/{schedule}/clone', 'ScheduleEventController@clone')->name('schedule_event.clone');
    Route::delete('schedule_event/{schedule}/destroy', 'ScheduleEventController@list_destroy')->name('schedule_event.list-destroy');
    Route::post('schedule_event/{schedule}', 'ScheduleEventController@store')->name('schedule_event.store');
    Route::resource('schedule_event', 'ScheduleEventController')->except('store');

    Route::get('schedule/{schedule}/size/league_size}/sb', 'ScheduleController@sb_size')->name('schedule.sb.size');
    Route::post('schedule', 'ScheduleController@store')->name('schedule.store')->middleware('can:create-schedules');
    Route::put('schedule/{schedule}', 'ScheduleController@update')->name('schedule.update')->middleware('can:update-schedules');
    Route::delete('schedule/{schedule}', 'ScheduleController@destroy')->name('schedule.destroy')->middleware('can:create-schedules');

    Route::put('message/{message}', 'MessageController@update')->name('message.update');
    Route::get('message/{message}/markread', 'MessageController@mark_as_read')->name('message.mark_as_read');
    Route::delete('message/{message}', 'MessageController@destroy')->name('message.destroy');


    Route::get('file/exports', 'FileDownloadController@get_file')->name('file.get');
    Route::get('archive/region/{region}/league', 'FileDownloadController@get_region_league_archive')->name('region_league_archive.get');
    Route::get('archive/region/{region}/teamware', 'FileDownloadController@get_region_teamware_archive')->name('region_teamware_archive.get');
    Route::get('archive/region/{region}/user/{user}', 'FileDownloadController@get_user_archive')->name('user_archive.get');
    Route::get('archive/club/{club}', 'FileDownloadController@get_club_archive')->name('club_archive.get');
    Route::get('archive/league/{league}', 'FileDownloadController@get_league_archive')->name('league_archive.get');
});
