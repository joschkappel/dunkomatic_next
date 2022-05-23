<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\HealthCheckResultsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\RegionGameController;
use App\Http\Controllers\ClubController;
use App\Http\Controllers\ClubGameController;
use App\Http\Controllers\ClubTeamController;
use App\Http\Controllers\AuditController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\ScheduleEventController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\ClubGymController;
use App\Http\Controllers\ClubMembershipController;
use App\Http\Controllers\LeagueMembershipController;
use App\Http\Controllers\RegionMembershipController;
use App\Http\Controllers\MembershipController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\LeagueTeamController;
use App\Http\Controllers\LeagueSizeController;
use App\Http\Controllers\LeagueSizeSchemeController;
use App\Http\Controllers\FileDownloadController;
use App\Http\Controllers\LeagueGameController;
use App\Http\Controllers\LeagueController;
use App\Http\Controllers\LeagueStateController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\Auth\SocialAuthController;

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

Route::get('/', function () { return redirect(app()->getLocale()); })->name('start');
Route::get('healthy', function () { return 'OK'; });
Route::get('health', HealthCheckResultsController::class);

Route::get('/auth/{provider}/redirect/{invitation?}', [SocialAuthController::class, 'redirectToOauth'])->name('oauth.redirect');
Route::get('/auth/{provider}/callback', [SocialAuthController::class, 'registerFromOauth'])->name('oauth.callback');


Route::group([
    'prefix' => '{language}',
    'where' => ['language' => '[a-zA-Z]{2}'],
    'middleware' => ['set.language',
                     'set.region',
                     'set.logcontext']
], function () {

    Route::redirect('/', 'de/signin');
    Route::get('signup', function () { return view('welcome_signup'); })->name('welcome_signup');
    Route::get('signin', function () { return view('welcome_signin'); })->name('welcome_signin');
    // Route::get('checkfire', function () { return view('broadcast.test'); });
    Route::get('cookies', function () { return view('app.cookie_info'); })->name('cookies');
    Route::get('impressum', function ($language) { return view('app.'.$language.'.impressum'); })->name('impressum');
    Route::get('dsgvo', function () { return view('app.dsgvo'); })->name('dsgvo');
    Route::get('faq', function ($language ) { return view('app.'.$language.'.faq', ['region'=>session('cur_region'), 'language'=>$language, 'user'=>Auth::user() ] ); })->name('faq');
    Route::get('captcha', [RegisterController::class, 'reloadCaptcha'] )->name('reload_captcha');

    Auth::routes(['verify' => true, 'middleware' => 'can:register']);
    Route::get('register_invited/{invitation}', [RegisterController::class, 'showRegistrationFormInvited'])->name('register.invited');
    Route::post('register_invited/{invitation}', [RegisterController::class, 'register_invitee'])->name('register.invitee');
    Route::get('apply/{user}', [SocialAuthController::class, 'showApplyForm'])->name('show.apply');
    Route::post('apply/{user}', [SocialAuthController::class, 'apply'])->name('apply');

    Route::middleware(['auth'])->group(function () {
        Route::get('home', [HomeController::class, 'home'])->name('home')->middleware('auth')->middleware('verified')->middleware('approved');
        Route::get('approval', [HomeController::class, 'approval'])->name('approval');

        Route::post('user/{user_id}/approve', [UserController::class, 'approve'])->name('admin.user.approve')->middleware('can:update-users');
        Route::get('user/{user}/edit', [UserController::class, 'edit'])->name('admin.user.edit')->middleware('auth')->middleware('can:update-users');
        Route::get('user/{user}/show', [UserController::class, 'show'])->name('admin.user.show');

        Route::get('region', [RegionController::class, 'index'])->name('region.index')->middleware('can:view-regions');
        Route::get('region/create', [RegionController::class, 'create'])->name('region.create')->middleware('can:create-regions');
        Route::get('region/{region}', [RegionController::class, 'show'])->name('region.show')->middleware('can:view-regions');
        Route::get('region/{region}/edit', [RegionController::class, 'edit'])->name('region.edit')->middleware('can:update-regions');
        Route::get('regions/dt', [RegionController::class, 'datatable'])->name('region.list.dt')->middleware('can:view-regions');
        Route::get('region/{region}/dashboard', [RegionController::class, 'dashboard'])->name('region.dashboard');
        Route::get('region/{region}/briefing', [RegionController::class, 'briefing'])->name('region.briefing')->middleware('can:view-regions');
        Route::get('region/{region}/game/upload', [RegionGameController::class, 'upload'])->name('region.upload.game');
        Route::post('region/{region}/game/ref/import', [RegionGameController::class, 'import_referees'])->name('region.import.refgame');

        Route::get('club/{club}/dashboard', [ClubController::class, 'dashboard'])->name('club.dashboard');
        Route::get('club/{club}/briefing', [ClubController::class, 'briefing'])->name('club.briefing')->middleware('can:view-clubs');
        Route::get('club/{club}/game/home', [ClubController::class, 'list_homegame'])->name('club.list.homegame')->middleware('can:view-games');
        Route::get('club/{club}', [ClubController::class, 'show'])->name('club.show')->middleware('can:view-clubs');
        Route::get('club/{club}/edit', [ClubController::class, 'edit'])->name('club.edit')->middleware('can:update-clubs');
        Route::get('club/{club}/team/dt', [ClubController::class, 'team_dt'])->name('club.team.dt');

        Route::get('club/{club}/game/upload', [ClubGameController::class, 'upload'])->name('club.upload.homegame');
        Route::post('club/{club}/game/import', [ClubGameController::class, 'import'])->name('club.import.homegame');
        Route::get('club/{club}/game/list_home', [ClubGameController::class, 'list_home'])->name('club.game.list_home')->middleware('can:view-games');
        Route::get('club/{club}/game/chart', [ClubGameController::class, 'chart'])->name('club.game.chart')->middleware('can:view-games');
        Route::get('club/{club}/team/pickchar', [ClubTeamController::class, 'pickchar'])->name('club.team.pickchar')->middleware('can:update-teams');
        Route::get('club/{club}/league_char_dt', [ClubTeamController::class, 'league_char_dt'])->name('club.league_char.dt')->middleware('can:view-teams');

        Route::get('audit/{audit}', [AuditController::class, 'show'])->name('audit.show');

        Route::group(['prefix' => 'region/{region}'], function () {
            Route::get('audit', [AuditController::class, 'index'])->name('audit.index');
            Route::get('audits/dt', [AuditController::class, 'datatable'])->name('audits.dt');

            Route::get('club', [ClubController::class, 'index'])->name('club.index')->middleware('can:view-clubs');
            Route::get('club/create', [ClubController::class, 'create'])->name('club.create')->middleware('can:create-clubs');
            Route::get('league/list', [LeagueController::class, 'list'])->name('league.list')->middleware('can:view-leagues');
            Route::get('league', [LeagueController::class, 'index'])->name('league.index')->middleware('can:view-leagues');
            Route::get('league/create', [LeagueController::class, 'create'])->name('league.create')->middleware('can:create-leagues');
            Route::get('league/list_mgmt', [LeagueController::class, 'list_mgmt'])->name('league.list_mgmt');
            Route::get('league/manage', [LeagueController::class, 'index_mgmt'])->name('league.index_mgmt');
            Route::get('schedule', [ScheduleController::class, 'index'])->name('schedule.index')->middleware('can:view-schedules');
            Route::get('schedule/create', [ScheduleController::class, 'create'])->name('schedule.create')->middleware('can:create-schedules');
            Route::get('schedule/compare/dt', [ScheduleController::class, 'compare_datatable'])->name('schedule.compare.dt')->middleware('can:view-schedules');
            Route::get('schedule/compare', [ScheduleController::class, 'compare'])->name('schedule.compare')->middleware('can:view-schedules');


            Route::get('user/new', [UserController::class, 'index_new'])->name('admin.user.index.new')->middleware('can:update-users');
            Route::get('user', [UserController::class, 'index'])->name('admin.user.index')->middleware('auth')->middleware('can:view-users');
            Route::get('user/dt', [UserController::class, 'datatable'])->name('admin.user.dt')->middleware('auth')->middleware('can:view-users');

            Route::get('user/{user}/message', [MessageController::class, 'index'])->name('message.index');
            Route::get('user/{user}/message/create', [MessageController::class, 'create'])->name('message.create');
            Route::get('user/{user}/message/dt', [MessageController::class, 'datatable_user'])->name('message.user.dt');

            Route::get('member', [MemberController::class, 'index'])->name('member.index')->middleware('can:view-members');
            Route::get('game', [GameController::class, 'index'])->name('game.index')->middleware('can:view-games');
            Route::get('game/datatable', [GameController::class, 'datatable'])->name('game.datatable')->middleware('can:view-games');

            Route::get('address/role/{role}', [AddressController::class, 'index_byrole'])->name('address.index_byrole')->middleware('can:view-members');
            Route::get('address/role/{role}/dt', [AddressController::class, 'index_byrole_dt'])->name('address.index_byrole.dt')->middleware('can:view-members');
        });

        Route::resource('club.gym', ClubGymController::class)->shallow()->except('store', 'update', 'destroy', 'show');

        Route::get('league/{league}/game/upload', [LeagueGameController::class, 'upload'])->name('league.upload.game');
        Route::post('league/{league}/game/import', [LeagueGameController::class, 'import'])->name('league.import.game');
        Route::get('league/{league}/dashboard', [LeagueController::class, 'dashboard'])->name('league.dashboard');
        Route::get('league/{league}/briefing', [LeagueController::class, 'briefing'])->name('league.briefing')->middleware('can:view-leagues');
        Route::get('league/{league}', [LeagueController::class, 'show'])->name('league.show')->middleware('can:view-leagues');
        Route::get('league/{league}/edit', [LeagueController::class, 'edit'])->name('league.edit')->middleware('can:update-leagues');
        Route::get('league/{league}/team/dt', [LeagueController::class, 'team_dt'])->name('league.team.dt');

        Route::get('league/{league}/game/dt', [LeagueGameController::class, 'datatable'])->name('league.game.dt');
        Route::resource('league.game', LeagueGameController::class)->shallow()->only(['index', 'create', 'edit']);

        Route::get('member/{member}/show', [MemberController::class, 'show'])->name('member.show')->middleware('can:view-members');
        Route::get('member/{member}', [MemberController::class, 'edit'])->name('member.edit')->middleware('can:update-members');

        Route::get('membership/club/{club}/member', [ClubMembershipController::class, 'create'])->name('membership.club.create');
        Route::get('membership/league/{league}/member', [LeagueMembershipController::class, 'create'])->name('membership.league.create');
        Route::get('membership/region/{region}/member', [RegionMembershipController::class, 'create'])->name('membership.region.create');

        Route::get('scheme/index', [LeagueSizeSchemeController::class, 'index'])->name('scheme.index');

        Route::get('team/league/plan/{club}', [TeamController::class, 'plan_leagues'])->name('team.plan-leagues');
        Route::post('team/league/plan/pivot', [TeamController::class, 'list_pivot'])->name('team.list-piv');
        Route::post('team/league/plan/chart', [TeamController::class, 'list_chart'])->name('team.list-chart');
        Route::post('team/league/plan/propose', [TeamController::class, 'propose_combination'])->name('team.propose');

        Route::resource('club.team', ClubTeamController::class)->shallow()->only('index', 'create', 'edit');

        Route::get('schedule_event/calendar', function () {
            return view('schedule/scheduleevent_cal');
        })->name('schedule_event.cal')->middleware('can:view-schedules');
        Route::get('schedule/{schedule}', [ScheduleController::class, 'show'])->name('schedule.show')->middleware('can:view-schedules');
        Route::get('schedule/{schedule}/edit', [ScheduleController::class, 'edit'])->name('schedule.edit')->middleware('can:update-schedules');

        Route::get('message/{message}/edit', [MessageController::class, 'edit'])->name('message.edit');
        Route::post('message/{message}/send', [MessageController::class, 'send'])->name('message.send');
        Route::post('message/{message}/copy', [MessageController::class, 'copy'])->name('message.copy');

        Route::get('calendar/league/{league}', [CalendarController::class, 'cal_league'])->name('cal.league')->middleware('can:view-games');
        Route::get('calendar/club/{club}', [CalendarController::class, 'cal_club'])->name('cal.club')->middleware('can:view-games');
        Route::get('calendar/club/{club}/home', [CalendarController::class, 'cal_club_home'])->name('cal.club.home')->middleware('can:view-games');
        Route::get('calendar/club/{club}/referee', [CalendarController::class, 'cal_club_referee'])->name('cal.club.referee')->middleware('can:view-games');
    });
});

Route::get('region/admin/sb', [RegionController::class, 'admin_sb'])->name('region.admin.sb')->middleware('set.logcontext');

Route::middleware(['auth',
                   'set.region',
                   'set.logcontext'])->group(function () {
    // APIs , no locale or language required !
    Route::redirect('/', '/de/signin');
    Route::redirect('home', '/de/home');

    Route::post('region', [RegionController::class, 'store'])->name('region.store')->middleware('can:create-regions');
    Route::put('region/{region}', [RegionController::class, 'update'])->name('region.update')->middleware('can:update-regions');
    Route::delete('region/{region}', [RegionController::class, 'destroy'])->name('region.destroy')->middleware('can:create-regions');
    Route::get('region/hq/sb', [RegionController::class, 'hq_sb'])->name('region.hq.sb');

    Route::delete('user/{user}', [UserController::class, 'destroy'])->name('admin.user.destroy')->middleware('can:update-users');
    Route::post('user/{user}/block', [UserController::class, 'block'])->name('admin.user.block')->middleware('can:update-users');
    Route::put('user/{user}', [UserController::class, 'update'])->name('admin.user.update')->middleware('can:update-users');
    Route::put('user/{user}/allowance', [UserController::class, 'allowance'])->name('admin.user.allowance')->middleware('can:update-users');

    Route::put('member/{member}', [MemberController::class, 'update'])->name('member.update')->middleware('can:update-members');
    Route::post('member', [MemberController::class, 'store'])->name('member.store')->middleware('can:create-members');
    Route::get('member/{member}/invite', [MemberController::class, 'invite'])->name('member.invite')->middleware('can:update-members');
    Route::delete('member/{member}', [MemberController::class, 'destroy'])->name('member.destroy')->middleware('can:create-members');

    Route::put('region/{region}/details', [RegionController::class, 'update_details'])->name('region.update_details')->middleware('can:update-regions');

    Route::put('club/{club}', [ClubController::class, 'update'])->name('club.update')->middleware('can:update-clubs');
    Route::delete('club/{club}', [ClubController::class, 'destroy'])->name('club.destroy')->middleware('can:create-clubs');
    Route::get('club/{club}/league/sb', [ClubController::class, 'sb_league'])->name('club.sb.league');

    Route::get('club/{club}/game/chart_home', [ClubGameController::class, 'chart_home'])->name('club.game.chart_home');
    Route::get('gym/{gym}/list', [ClubGymController::class, 'sb_gym'])->name('gym.sb.gym');
    Route::get('club/{club}/list/gym', [ClubGymController::class, 'sb_club'])->name('gym.sb.club');
    Route::get('team/{team}/list/gym', [ClubGymController::class, 'sb_team'])->name('gym.sb.team');
    Route::resource('club.gym', ClubGymController::class)->shallow()->only('store', 'update', 'destroy');

    Route::group(['prefix' => 'region/{region}'], function () {
        Route::get('club/list', [ClubController::class, 'list'])->name('club.list')->middleware('can:view-clubs');
        Route::post('club', [ClubController::class, 'store'])->name('club.store')->middleware('can:create-clubs');
        Route::get('club/sb', [ClubController::class, 'sb_region'])->name('club.sb.region');

        Route::post('league', [LeagueController::class, 'store'])->name('league.store')->middleware('can:create-leagues');
        Route::get('schedule/list', [ScheduleController::class, 'list'])->name('schedule.list')->middleware('can:view-schedules');
        Route::get('league/sb', [LeagueController::class, 'sb_region'])->name('league.sb.region');
        Route::get('schedule/sb', [ScheduleController::class, 'sb_region'])->name('schedule.sb.region');
        Route::get('member/sb', [MemberController::class, 'sb_region'])->name('member.sb.region');

        Route::post('user/{user}/message', [MessageController::class, 'store'])->name('message.store');

        Route::get('member/datatable', [MemberController::class, 'datatable'])->name('member.datatable')->middleware('can:view-members');

        Route::get('league/team_register/dt', [LeagueController::class, 'team_register_dt'])->name('league.team_register.dt');
        Route::get('schedule/size/{size}/sb', [ScheduleController::class, 'sb_region_size'])->name('schedule.sb.region_size');
        Route::get('schedule_event/list-cal', [ScheduleEventController::class, 'list_cal'])->name('schedule_event.list-cal')->middleware('can:view-schedules');

        Route::get('league/team', [RegionController::class, 'league_team_chart'])->name('region.league.team.chart')->middleware('can:view-leagues');
        Route::get('league/state', [RegionController::class, 'league_state_chart'])->name('region.league.state.chart')->middleware('can:view-leagues');
        Route::get('league/socio', [RegionController::class, 'league_socio_chart'])->name('region.league.socio.chart')->middleware('can:view-leagues');
        Route::get('club/team', [RegionController::class, 'club_team_chart'])->name('region.club.team.chart')->middleware('can:view-clubs');
        Route::get('club/member', [RegionController::class, 'club_member_chart'])->name('region.club.member.chart')->middleware('can:view-clubs');
        Route::get('game/noreferee', [RegionController::class, 'game_noreferee_chart'])->name('region.game.noreferee.chart')->middleware('can:view-games');
        Route::get('region/club', [RegionController::class, 'region_club_chart'])->name('region.region.club.chart')->middleware('can:view-regions');
        Route::get('region/league', [RegionController::class, 'region_league_chart'])->name('region.region.league.chart')->middleware('can:view-regions');
    });

    Route::post('league/{league}/club', [LeagueTeamController::class, 'assign_clubs'])->name('league.assign-clubs')->middleware('can:update-leagues');
    Route::delete('league/{league}/club/{club}', [LeagueTeamController::class, 'deassign_club'])->name('league.deassign-club')->middleware('can:update-leagues');
    Route::put('league/{league}/team/{team}', [LeagueTeamController::class, 'league_register_team'])->name('league.register.team')->middleware('can:update-teams');
    Route::delete('league/{league}/team/{team}', [LeagueTeamController::class, 'league_unregister_team'])->name('league.unregister.team')->middleware('can:update-teams');
    Route::put('team/{team}/league', [LeagueTeamController::class, 'team_register_league'])->name('team.register.league')->middleware('can:update-teams');
    Route::post('league/{league}/team', [LeagueTeamController::class, 'inject'])->name('league.team.inject')->middleware('can:update-teams');
    Route::delete('league/{league}/team', [LeagueTeamController::class, 'withdraw'])->name('league.team.withdraw')->middleware('can:update-teams');
    Route::post('league/{league}/pickchar', [LeagueTeamController::class, 'pick_char'])->name('league.team.pickchar')->middleware('can:update-teams');
    Route::post('league/{league}/releasechar', [LeagueTeamController::class, 'release_char'])->name('league.team.releasechar')->middleware('can:update-teams');

    Route::get('league/{league}/freechar/sb', [LeagueController::class, 'sb_freechars'])->name('league.sb_freechar');
    Route::get('league/{league}/club/sb', [LeagueController::class, 'sb_club'])->name('league.sb.club');
    Route::post('league/{league}/state', [LeagueStateController::class, 'change_state'])->name('league.state.change')->middleware('can:update-leagues');
    Route::put('league/{league}', [LeagueController::class, 'update'])->name('league.update')->middleware('can:update-leagues');
    Route::delete('league/{league}', [LeagueController::class, 'destroy'])->name('league.destroy')->middleware('can:create-leagues');

    Route::post('membership/club/{club}/member/{member}', [ClubMembershipController::class, 'add'])->name('membership.club.add');
    Route::delete('membership/club/{club}/member/{member}', [ClubMembershipController::class, 'destroy'])->name('membership.club.destroy');
    Route::post('membership/league/{league}/member/{member}', [LeagueMembershipController::class, 'add'])->name('membership.league.add');
    Route::delete('membership/league/{league}/member/{member}', [LeagueMembershipController::class, 'destroy'])->name('membership.league.destroy');
    Route::post('membership/region/{region}/member/{member}', [RegionMembershipController::class, 'add'])->name('membership.region.add');
    Route::delete('membership/region/{region}/member/{member}', [RegionMembershipController::class, 'destroy'])->name('membership.region.destroy');
    Route::put('membership/{membership}', [MembershipController::class, 'update'])->name('membership.update');
    Route::delete('membership/{membership}', [MembershipController::class, 'destroy'])->name('membership.destroy');

    Route::delete('league/{league}/game', [LeagueGameController::class, 'destroy_game'])->name('league.game.destroy');
    Route::get('league/{league}/game/{game_no}', [LeagueGameController::class, 'show_by_number'])->name('league.game.show_bynumber');
    Route::delete('league/{league}/game/noshow', [LeagueGameController::class, 'destroy_noshow_game'])->name('league.game.destroy_noshow');
    Route::put('game/{game}/home', [LeagueGameController::class, 'update_home'])->name('game.update_home');
    Route::resource('league.game', LeagueGameController::class)->shallow()->except(['index', 'create', 'edit']);

    Route::get('league/{league}/team/sb', [TeamController::class, 'sb_league'])->name('league.team.sb');
    Route::get('team/league/{league}/free/sb', [TeamController::class, 'sb_freeteam'])->name('team.free.sb');
    Route::post('team/league/plan', [TeamController::class, 'store_plan'])->name('team.store-plan')->middleware('can:update-teams');

    Route::resource('club.team', ClubTeamController::class)->shallow()->except('index', 'create', 'edit');

    Route::post('role/index', [RoleController::class, 'index'])->name('role.index');

    Route::get('scheme/{size}/list_piv', [LeagueSizeSchemeController::class, 'list_piv'])->name('scheme.list_piv');
    Route::get('size/index', [LeagueSizeController::class, 'index'])->name('size.index');

    Route::get('schedule_event/{schedule}/list', [ScheduleEventController::class, 'list'])->name('schedule_event.list');
    Route::get('schedule_event/{schedule}/dt', [ScheduleEventController::class, 'datatable'])->name('schedule_event.dt');
    Route::post('schedule_event/{schedule}/shift', [ScheduleEventController::class, 'shift'])->name('schedule_event.shift');
    Route::post('schedule_event/{schedule}/clone', [ScheduleEventController::class, 'clone'])->name('schedule_event.clone');
    Route::delete('schedule_event/{schedule}/destroy', [ScheduleEventController::class, 'list_destroy'])->name('schedule_event.list-destroy');
    Route::post('schedule_event/{schedule}', [ScheduleEventController::class, 'store'])->name('schedule_event.store');
    Route::resource('schedule_event', ScheduleEventController::class)->except('store');

    Route::get('schedule/{schedule}/size/league_size}/sb', [ScheduleController::class, 'sb_size'])->name('schedule.sb.size');
    Route::post('schedule', [ScheduleController::class, 'store'])->name('schedule.store')->middleware('can:create-schedules');
    Route::put('schedule/{schedule}', [ScheduleController::class, 'update'])->name('schedule.update')->middleware('can:update-schedules');
    Route::delete('schedule/{schedule}', [ScheduleController::class, 'destroy'])->name('schedule.destroy')->middleware('can:create-schedules');

    Route::put('message/{message}', [MessageController::class, 'update'])->name('message.update');
    Route::get('message/{message}/markread', [MessageController::class, 'mark_as_read'])->name('message.mark_as_read');
    Route::delete('message/{message}', [MessageController::class, 'destroy'])->name('message.destroy');


    Route::get('file/exports', [FileDownloadController::class, 'get_file'])->name('file.get');
    Route::get('archive/region/{region}/league', [FileDownloadController::class, 'get_region_league_archive'])->name('region_league_archive.get');
    Route::get('archive/region/{region}/teamware', [FileDownloadController::class, 'get_region_teamware_archive'])->name('region_teamware_archive.get');
    Route::get('archive/region/{region}/user/{user}', [FileDownloadController::class, 'get_user_archive'])->name('user_archive.get');
    Route::get('archive/club/{club}', [FileDownloadController::class, 'get_club_archive'])->name('club_archive.get');
    Route::get('archive/league/{league}', [FileDownloadController::class, 'get_league_archive'])->name('league_archive.get');
});
