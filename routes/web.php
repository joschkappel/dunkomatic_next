<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Club;

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

Route::redirect('/', '/en');


Route::group(['prefix' => '{language}'], function(){
  Route::get('/', function () {
      return view('welcome');
  })->name('welcome');

  Auth::routes();

  Route::get('home', function() {
      return view('home');
  })->name('home')->middleware('auth');

  Route::get('club/index_stats', 'ClubController@index_stats')->name('club.index_stats');
  Route::get('club/{id}/list', 'ClubController@dashboard')->name('club.dashboard');
  Route::get('club/list', 'ClubController@list')->name('club.list');
  Route::get('club/{club}/game/home','ClubController@edit_homegame')->name('club.edit.homegame');
  Route::get('club/{club}/game/upload','ClubGameController@upload')->name('club.upload.homegame');
  Route::post('club/{club}/game/import','ClubGameController@import')->name('club.import.homegame');
  Route::get('club/{club}/game/list_home', 'ClubGameController@list_home')->name('club.game.list_home');
  Route::get('club/{club}/game/chart', 'ClubGameController@chart')->name('club.game.chart');

  Route::resource('club', 'ClubController')->except('store','update','destroy');
  Route::resource('club.gym', 'ClubGymController')->shallow()->except('store','update','destroy','show');

  Route::get('league/index_stats', 'LeagueController@index_stats')->name('league.index_stats');
  Route::get('league/list_stats', 'LeagueController@list_stats')->name('league.list_stats');
  Route::get('league/{id}/list', 'LeagueController@dashboard')->name('league.dashboard');
  Route::resource('league', 'LeagueController')->except('store','update','destroy');
  Route::resource('league.game', 'LeagueGameController')->shallow()->only(['index','create','edit']);;

  Route::resource('member', 'MemberController')->only(['show']);
  Route::resource('club.memberrole', 'ClubMemberRoleController')->only(['index','create','edit']);
  Route::resource('league.memberrole', 'LeagueMemberRoleController')->only(['index','create','edit']);
  Route::resource('memberrole', 'MemberRoleController')->only(['show']);

  Route::get('scheme/index', 'LeagueSchemeController@index')->name('scheme.index');

  Route::get('team/league/plan/{club}', 'TeamController@plan_leagues')->name('team.plan-leagues');
  Route::post('team/league/plan/pivot', 'TeamController@list_pivot')->name('team.list-piv');
  Route::post('team/league/plan/chart', 'TeamController@list_chart')->name('team.list-chart');
  Route::post('team/league/plan/propose', 'TeamController@propose_combination')->name('team.propose');
  Route::post('team/league/plan/store', 'TeamController@store_plan')->name('team.store-plan');
  Route::resource('club.team', 'ClubTeamController')->shallow()->only('index','create','edit');;

  Route::get('schedule_event/calendar',function() { return view('schedule/scheduleevent_cal');})->name('schedule_event.cal');

  Route::get('schedule/index_piv', function() { return view('schedule/schedules_list');})->name('schedule.index_piv');
  Route::resource('schedule', 'ScheduleController')->only('index','create','edit');

});

Route::get('club/list_sel', 'ClubController@list_select')->name('club.list_sel');
Route::get('club/list_stats', 'ClubController@list_stats')->name('club.list_stats');
Route::resource('club', 'ClubController')->only('store','update','destroy');
Route::get('club/{club}/game/chart_home', 'ClubGameController@chart_home')->name('club.game.chart_home');
Route::get('club/{club}/gym/{gym_no}', 'ClubGymController@show')->name('club.gym.show');
Route::resource('club.gym', 'ClubGymController')->shallow()->only('store','update','destroy');

Route::get('league/list', 'LeagueController@list')->name('league.list');
Route::get('league/list/club/{club}', 'LeagueController@list_select4club')->name('league.list_sel4club');
Route::delete('league/{league}/club/{club}', 'LeagueController@deassign_club')->name('league.deassign-club');
Route::post('league/{league}/club', 'LeagueController@assign_club')->name('league.assign-club');
Route::resource('league', 'LeagueController')->only('store','update','destroy');

Route::resource('club.memberrole', 'ClubMemberRoleController')->only(['store','update']);
Route::resource('league.memberrole', 'LeagueMemberRoleController')->only(['store','update']);
Route::resource('memberrole', 'MemberRoleController')->only(['destroy']);

Route::delete('league/{league}/game', 'LeagueGameController@destroy_game')->name('league.game.destroy');
Route::delete('league/{league}/game/noshow', 'LeagueGameController@destroy_noshow_game')->name('league.game.destroy_noshow');
Route::resource('league.game', 'LeagueGameController')->shallow()->except(['index','create','edit']);;

Route::put('team/league', 'TeamController@assign_league')->name('team.assign-league');
Route::delete('team/league', 'TeamController@deassign_league')->name('team.deassign-league');

Route::resource('club.team', 'ClubTeamController')->shallow()->except('index','create','edit');;

Route::post('role/index', 'RoleController@index')->name('role.index');

Route::get('scheme/{size}/list_piv', 'LeagueSchemeController@list_piv')->name('scheme.list_piv');
Route::get('size/index', 'LeagueSizeController@index')->name('size.index');

Route::get('schedule_event/list-cal', 'ScheduleEventController@list_cal')->name('schedule_event.list-cal');
Route::post('schedule_event/list-piv', 'ScheduleEventController@list_piv')->name('schedule_event.list-piv');
Route::get('schedule_event/list/{id}', 'ScheduleEventController@list')->name('schedule_event.list');
Route::get('schedule_event/list-dt/{id}', 'ScheduleEventController@list_dt')->name('schedule_event.list-dt');
Route::post('schedule_event/shift', 'ScheduleEventController@shift')->name('schedule_event.shift');
Route::post('schedule_event/clone', 'ScheduleEventController@clone')->name('schedule_event.clone');
Route::delete('schedule_event/list-destroy/{id}', 'ScheduleEventController@list_destroy')->name('schedule_event.list-destroy');
Route::resource('schedule_event', 'ScheduleEventController');

Route::delete('schedule/delete/{id}', 'ScheduleController@destroy')->name('schedule.delete');
Route::get('schedule/list', 'ScheduleController@list')->name('schedule.list');
Route::get('schedule/list_sel', 'ScheduleController@list_select')->name('schedule.list_sel');
Route::get('schedule/size/{size}/list_sel', 'ScheduleController@list_size_select')->name('schedule.list_size_sel');
Route::resource('schedule', 'ScheduleController')->except('index','create','edit');
