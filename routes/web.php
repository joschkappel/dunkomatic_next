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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', function() {
    return view('home');
})->name('home')->middleware('auth');

Route::get('club/list', 'ClubController@list');
Route::get('club/delete/{id}','ClubController@delete')->name('club.delete');
Route::get('club/{id}/list', 'ClubController@dashboard')->name('club.dashboard');
Route::get('club/list_sel', 'ClubController@list_select')->name('club.list_sel');
Route::resource('club', 'ClubController');

Route::resource('club.gym', 'ClubGymController')->shallow();

Route::get('scheme/index', 'LeagueSchemeController@index');
Route::get('scheme/{size}/list_piv', 'LeagueSchemeController@list_piv');
Route::get('size/index', 'LeagueSizeController@index');

Route::get('schedule_event/list-cal', 'ScheduleEventController@list_cal')->name('schedule_event.list-cal');
Route::post('schedule_event/list-piv', 'ScheduleEventController@list_piv')->name('schedule_event.list-piv');
Route::get('schedule_event/calendar',function() { return view('schedule/scheduleevent_cal');})->name('schedule_event.cal');
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
Route::get('schedule/index_piv', function() { return view('schedule/schedules_list');})->name('schedule.index_piv');
Route::resource('schedule', 'ScheduleController');

Route::get('league/list', 'LeagueController@list')->name('league.list');
Route::get('league/{id}/list', 'LeagueController@dashboard')->name('league.dashboard');
Route::get('league/list_sel', 'LeagueController@list_select')->name('league.list_sel');
Route::get('league/list/club/{club}', 'LeagueController@list_select4club')->name('league.list_sel4club');
Route::resource('league', 'LeagueController');
Route::delete('league/{league}/club/{club}', 'LeagueController@deassign_club')->name('league.deassign-club');
Route::post('league/{league}/club', 'LeagueController@assign_club')->name('league.assign-club');
Route::resource('league.game', 'LeagueGameController')->shallow();

Route::put('team/league', 'TeamController@assign_league')->name('team.assign-league');
Route::delete('team/league', 'TeamController@deassign_league')->name('team.deassign-league');
Route::get('team/league/plan/{club}', 'TeamController@plan_leagues')->name('team.plan-leagues');
Route::post('team/league/plan/pivot', 'TeamController@list_pivot')->name('team.list-piv');
Route::post('team/league/plan/chart', 'TeamController@list_chart')->name('team.list-chart');
Route::post('team/league/plan/propose', 'TeamController@propose_combination')->name('team.propose');
Route::post('team/league/plan/store', 'TeamController@store_plan')->name('team.store-plan');
Route::resource('club.team', 'ClubTeamController')->shallow();

Route::post('role/index', 'RoleController@index')->name('role.index');
Route::resource('member', 'MemberController')->only(['show']);
Route::resource('club.memberrole', 'ClubMemberRoleController')->only(['index','store','create','update','edit']);
Route::resource('league.memberrole', 'LeagueMemberRoleController')->only(['index','store','create','update','edit']);
//Route::get('memberrole/league/create', 'MemberRoleController@create_league')->name('memberrole.league.create');
//Route::get('memberrole/club/{club}/create', 'MemberRoleController@create_club')->name('memberrole.club.create');
Route::resource('memberrole', 'MemberRoleController')->only(['destroy', 'show']);
