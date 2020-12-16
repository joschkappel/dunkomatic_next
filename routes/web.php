<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

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
});

Route::group([
  'prefix' => '{language}',
  'where' => ['language' => '[a-zA-Z]{2}'],
  'middleware' => 'setLanguage'], function() {

  Route::get('/', function () {
      return view('welcome');
  })->name('welcome');

  Auth::routes(['verify' => true]);

  Route::middleware(['auth'])->group(function () {
    Route::get('/home', 'HomeController@home')->name('home')->middleware('auth')->middleware('verified')->middleware('approved');
    Route::get('/approval', 'HomeController@approval')->name('approval');

    Route::get('user/new', 'UserController@index_new')->name('admin.user.index.new')->middleware('auth')->middleware('regionadmin');
    Route::get('user/dt', 'UserController@datatable')->name('admin.user.dt')->middleware('auth')->middleware('regionadmin');
    Route::post('user/{user_id}/approve', 'UserController@approve')->name('admin.user.approve')->middleware('auth')->middleware('regionadmin');
    Route::get('user/{user}/edit', 'UserController@edit')->name('admin.user.edit')->middleware('auth')->middleware('regionadmin');
    Route::get('user/{user}/show', 'UserController@show')->name('admin.user.show');
    Route::get('user', 'UserController@index')->name('admin.user.index')->middleware('auth')->middleware('regionadmin');
    Route::get('audit', 'AuditController@index')->name('admin.audit.index')->middleware('auth')->middleware('regionadmin');
    Route::get('audit/dt', 'AuditController@datatable')->name('admin.audit.dt')->middleware('auth')->middleware('regionadmin');
    Route::get('region/{region}', 'RegionController@edit')->name('region.edit')->middleware('regionadmin');
    Route::get('region', 'RegionController@index')->name('region.index');
    Route::get('region/list/dt', 'RegionController@list_dt')->name('region.list.dt');

    Route::get('club/index_stats', 'ClubController@index_stats')->name('club.index_stats');
    Route::get('club/{club}/list', 'ClubController@dashboard')->name('club.dashboard');
    Route::get('club/{club}/game/home','ClubController@list_homegame')->name('club.list.homegame');
    Route::get('club/{club}/game/upload','ClubGameController@upload')->name('club.upload.homegame');
    Route::post('club/{club}/game/import','ClubGameController@import')->name('club.import.homegame');
    Route::get('club/{club}/game/list_home', 'ClubGameController@list_home')->name('club.game.list_home');
    Route::get('club/{club}/game/chart', 'ClubGameController@chart')->name('club.game.chart');
    Route::get('club/{club}/team/pickchar', 'ClubTeamController@pickchar')->name('club.team.pickchar');

    Route::resource('club', 'ClubController')->except('store','update','destroy');
    Route::resource('club.gym', 'ClubGymController')->shallow()->except('store','update','destroy','show');

    Route::get('league/index_stats', 'LeagueController@index_stats')->name('league.index_stats');
    Route::get('league/{league}/list', 'LeagueController@dashboard')->name('league.dashboard');
    Route::get('league/{league}/game/dt', 'LeagueGameController@datatable')->name('league.game.dt');
    Route::resource('league', 'LeagueController')->except('store','update','destroy');
    Route::resource('league.game', 'LeagueGameController')->shallow()->only(['index','create','edit']);

    Route::resource('member', 'MemberController')->only(['show']);
    Route::get('membership/club/{club}', 'ClubMembershipController@index')->name('membership.club.index');
    Route::get('membership/club/{club}/member', 'ClubMembershipController@create')->name('membership.club.create');
    Route::get('membership/club/{club}/member/{member}', 'ClubMembershipController@edit')->name('membership.club.edit');
    Route::get('membership/league/{league}', 'LeagueMembershipController@index')->name('membership.league.index');
    Route::get('membership/league/{league}/member', 'LeagueMembershipController@create')->name('membership.league.create');
    Route::get('membership/league/{league}/member/{member}', 'LeagueMembershipController@edit')->name('membership.league.edit');


    Route::resource('membership', 'MembershipController')->only(['show']);

    Route::get('scheme/index', 'LeagueSizeSchemeController@index')->name('scheme.index');

    Route::get('team/league/plan/{club}', 'TeamController@plan_leagues')->name('team.plan-leagues');
    Route::post('team/league/plan/pivot', 'TeamController@list_pivot')->name('team.list-piv');
    Route::post('team/league/plan/chart', 'TeamController@list_chart')->name('team.list-chart');
    Route::post('team/league/plan/propose', 'TeamController@propose_combination')->name('team.propose');
    Route::resource('club.team', 'ClubTeamController')->shallow()->only('index','create','edit');;

    Route::get('schedule_event/calendar',function() { return view('schedule/scheduleevent_cal');})->name('schedule_event.cal');

    Route::get('schedule/index_piv', function() { return view('schedule/schedules_list');})->name('schedule.index_piv');
    Route::resource('schedule', 'ScheduleController')->only('index','create','edit');

    Route::resource('message', 'MessageController')->only('index','create','edit');
    Route::get('message/user/{user}/dt', 'MessageController@list_user_dt')->name('message.user.dt');
    Route::get('message/region/{region}/dt', 'MessageController@list_region_dt')->name('message.region.dt');
    Route::post('message/{message}/send', 'MessageController@send')->name('message.send');

    Route::get('calendar/league/{league}', 'CalendarController@cal_league')->name('cal.league');
    Route::get('calendar/club/{club}', 'CalendarController@cal_club')->name('cal.club');
  });

});

Route::get('region/admin/sb', 'RegionController@admin_sb')->name('region.admin.sb');

Route::middleware(['auth'])->group(function () {
  // APIs , no locale or language required !
  Route::redirect('home', '/de/home');
  Route::delete('user/{user}', 'UserController@destroy')->name('admin.user.destroy')->middleware('auth')->middleware('regionadmin');
  Route::post('user/{user}/block', 'UserController@block')->name('admin.user.block')->middleware('auth')->middleware('regionadmin');
  Route::put('user/{user_id}', 'UserController@update')->name('admin.user.update')->middleware('auth');
  Route::put('user/{user}/allowance', 'UserController@allowance')->name('admin.user.allowance')->middleware('auth')->middleware('regionadmin');
  Route::put('member/{member}', 'MemberController@update')->name('member.update');
  Route::post('member', 'MemberController@store')->name('member.store');
  Route::put('region/{region}', 'RegionController@update')->name('region.update')->middleware('auth')->middleware('regionadmin');
  Route::post('region', 'RegionController@create')->name('region.create')->middleware('auth')->middleware('regionadmin');

  Route::resource('club', 'ClubController')->only('store','update','destroy');
  Route::get('club/{club}/game/chart_home', 'ClubGameController@chart_home')->name('club.game.chart_home');
  Route::get('club/{club}/gym/{gym_no}', 'ClubGymController@show')->name('club.gym.show');
  Route::get('club/{club}/list/gym', 'ClubGymController@sb_club')->name('gym.sb.club');
  Route::resource('club.gym', 'ClubGymController')->shallow()->only('store','update','destroy');

  Route::group(['prefix' => '{region}'], function () {
    Route::get('region/set', 'RegionController@set_region')->name('region.set');
    Route::get('club/list', 'ClubController@list')->name('club.list');
    Route::get('club/list_stats', 'ClubController@list_stats')->name('club.list_stats');
    Route::get('club/region/sb', 'ClubController@sb_region')->name('club.sb.region');
    Route::get('league/list', 'LeagueController@list')->name('league.list');
    Route::get('league/list_stats', 'LeagueController@list_stats')->name('league.list_stats');
    Route::get('league/region/sb', 'LeagueController@sb_region')->name('league.sb.region');
    Route::get('schedule/list', 'ScheduleController@list')->name('schedule.list');
    Route::get('schedule/region/sb', 'ScheduleController@sb_region')->name('schedule.sb.region');
    Route::get('schedule_event/list-cal', 'ScheduleEventController@list_cal')->name('schedule_event.list-cal');
    Route::post('message', 'MessageController@store')->name('message.store');
    Route::put('message/{message}', 'MessageController@update')->name('message.update');
  });

  Route::get('league/{league}/freechar/sb', 'LeagueController@sb_freechars')->name('league.sb_freechar');
  Route::get('league/club/{club}/sb', 'LeagueController@sb_club')->name('league.sb.club');
  Route::delete('league/{league}/club/{club}', 'LeagueController@deassign_club')->name('league.deassign-club');
  Route::post('league/{league}/club', 'LeagueController@assign_club')->name('league.assign-club');
  Route::resource('league', 'LeagueController')->only('store','update','destroy');

  Route::post('membership/club/{club}/member', 'ClubMembershipController@store')->name('membership.club.store');
  Route::put('membership/club/{club}/member/{member}', 'ClubMembershipController@update')->name('membership.club.update');
  Route::delete('membership/club/{club}/member/{member}', 'ClubMembershipController@destroy')->name('membership.club.destroy');
  Route::post('membership/league/{league}/member', 'LeagueMembershipController@store')->name('membership.league.store');
  Route::put('membership/league/{league}/member/{member}', 'LeagueMembershipController@update')->name('membership.league.update');
  Route::delete('membership/league/{league}/member/{member}', 'LeagueMembershipController@destroy')->name('membership.league.destroy');

  Route::resource('membership', 'MembershipController')->only(['destroy']);

  Route::delete('league/{league}/game', 'LeagueGameController@destroy_game')->name('league.game.destroy');
  Route::delete('league/{league}/game/noshow', 'LeagueGameController@destroy_noshow_game')->name('league.game.destroy_noshow');
  Route::delete('league/{league}/team', 'TeamController@withdraw')->name('league.team.withdraw');
  Route::post('league/{league}/team', 'TeamController@inject')->name('league.team.inject');
  Route::post('league/{league}/char', 'TeamController@pick_char')->name('league.team.pickchar');
  Route::get('league/{league}/team/sb', 'TeamController@sb_league')->name('league.team.sb');
  Route::resource('league.game', 'LeagueGameController')->shallow()->except(['index','create','edit']);;

  Route::put('team/league', 'TeamController@assign_league')->name('team.assign-league');
  Route::delete('team/league', 'TeamController@deassign_league')->name('team.deassign-league');
  Route::get('team/league/{league}/free/sb', 'TeamController@sb_freeteam')->name('team.free.sb');
  Route::post('team/league/plan', 'TeamController@store_plan')->name('team.store-plan');

  Route::resource('club.team', 'ClubTeamController')->shallow()->except('index','create','edit');;

  Route::post('role/index', 'RoleController@index')->name('role.index');
  Route::get('member/region/{region}', 'MemberController@sb_region')->name('member.sb.region');

  Route::get('scheme/{size}/list_piv', 'LeagueSizeSchemeController@list_piv')->name('scheme.list_piv');
  Route::get('size/index', 'LeagueSizeController@index')->name('size.index');

  Route::post('schedule_event/list-piv', 'ScheduleEventController@list_piv')->name('schedule_event.list-piv');
  Route::get('schedule_event/{schedule}/list', 'ScheduleEventController@list')->name('schedule_event.list');
  Route::get('schedule_event/list-dt/{id}', 'ScheduleEventController@list_dt')->name('schedule_event.list-dt');
  Route::post('schedule_event/{schedule}/shift', 'ScheduleEventController@shift')->name('schedule_event.shift');
  Route::post('schedule_event/{schedule}/clone', 'ScheduleEventController@clone')->name('schedule_event.clone');
  Route::delete('schedule_event/{schedule}/destroy', 'ScheduleEventController@list_destroy')->name('schedule_event.list-destroy');
  Route::post('schedule_event/{schedule}', 'ScheduleEventController@store')->name('schedule_event.store');
  Route::resource('schedule_event', 'ScheduleEventController')->except('store');

  Route::delete('schedule/delete/{id}', 'ScheduleController@destroy')->name('schedule.delete');
  Route::get('schedule/{schedule}/size/league_size}/sb', 'ScheduleController@sb_size')->name('schedule.sb.size');
  Route::resource('schedule', 'ScheduleController')->except('index','create','edit');
  Route::resource('message', 'MessageController')->except('index','create','edit');

  Route::get('file/exports/{season}/{region}/{type}/{file}', 'FileDownloadController@get_file')->name('file.get');
  Route::get('archive/user/{user}', 'FileDownloadController@get_user_archive')->name('user_archive.get');
  Route::get('archive/club/{club}', 'FileDownloadController@get_club_archive')->name('club_archive.get');
  Route::get('archive/league/{league}', 'FileDownloadController@get_league_archive')->name('league_archive.get');
});
