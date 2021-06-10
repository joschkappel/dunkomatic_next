<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Events\Dispatcher;
use App\Events\BuildingMenu;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

use App\Models\Setting;
use App\Models\Region;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
      if ($this->app->environment('local')) {
        $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
        $this->app->register(TelescopeServiceProvider::class);
      }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Dispatcher $events)
    {
          try {
                  $pdo = DB::connection()->getPdo();
                  if (DB::connection()->getDatabaseName()){
                      // Log::info('Yes! Successfully connected to the DB: ' . DB::connection()->getDatabaseName());
                      if (Schema::hasTable('settings')){
                       config([
                         'global' => Setting::all([
                             'name','value'
                         ])
                         ->keyBy('name') // key every setting by its name
                         ->transform(function ($setting) {
                              return $setting->value; // return only the value
                         })
                         ->toArray() // make it an array
                       ]);
                      };

                  } else {
                      Log::warning("Could not find the database. Please check your configuration.");
                  }
               } catch (\Exception $e) {
                  Log::warning("Could not open connection to database server.  Please check your configuration.");
               }

          $events->listen(BuildingMenu::class, function (BuildingMenu $event) {
                $clubmenu = array();
                $clubmenu['text'] = trans_choice('club.club', 2);
                $clubmenu['icon_color'] = 'orange';
                $clubmenu['icon'] = 'fas fa-basketball-ball';

                $smenu['text'] = __('club.menu.list');
                $smenu['url']  = route('club.index', ['language'=>app()->getLocale()]);
                $smenu['icon_color'] = 'orange';
                $smenu['icon'] =  'fas fa-list';
                $smenu['can'] = 'manage-clubs';
                $clubmenu['submenu'][] = $smenu;

                foreach (Auth::user()->member()->first()->clubs()->get()->unique() as $c) {
                    $smenu['text'] = $c->shortname;
                    $smenu['url']  = route('club.dashboard', ['language'=>app()->getLocale(), 'club'=>$c ]);
                    $smenu['icon_color'] = 'orange';
                    $smenu['icon'] =  'fas fa-list';
                    $smenu['can'] = 'manage-clubs';
                    $clubmenu['submenu'][] = $smenu;
                };

                $smenu['text'] = __('club.menu.stats');
                $smenu['url']  = route('club.index_stats', app()->getLocale());
                $smenu['icon_color'] = 'orange';
                $smenu['icon'] =  'fas fa-chart-bar';
                $smenu['can'] = 'manage-clubs';
                $clubmenu['submenu'][] = $smenu;
                $event->menu->add($clubmenu);

                $leaguemenu = array();
                $leaguemenu['text'] = trans_choice('league.league', 2);
                $leaguemenu['icon_color'] = 'yellow';
                $leaguemenu['icon'] = 'fas fa-trophy';

                $smenu['text'] = __('league.menu.list');
                $smenu['url']  = route('league.index', app()->getLocale());
                $smenu['icon_color'] = 'yellow';
                $smenu['icon'] =  'fas fa-list';
                $smenu['can'] = 'manage-leagues';
                $leaguemenu['submenu'][] = $smenu;

                foreach (Auth::user()->member()->first()->leagues()->get()->unique() as $l) {
                    $smenu['text'] = $l->shortname;
                    $smenu['url']  = route('league.dashboard', ['language'=>app()->getLocale(), 'league'=>$l ]);
                    $smenu['icon_color'] = 'yellow';
                    $smenu['icon'] =  'fas fa-list';
                    $smenu['can'] = 'manage-leagues';
                    $leaguemenu['submenu'][] = $smenu;
                 };

                $smenu['text'] = __('league.menu.stats');
                $smenu['url']  = route('league.index_stats', app()->getLocale());
                $smenu['icon_color'] = 'yellow';
                $smenu['icon'] =  'fas fa-chart-bar';
                $smenu['can'] = 'manage-leagues';
                $leaguemenu['submenu'][] = $smenu;
                $event->menu->add($leaguemenu);

                $event->menu->add([
                  'text' => trans_choice('schedule.scheme', 2),
                  'url'  => route('scheme.index', app()->getLocale()),
                  'icon' => 'fas fa-people-arrows',
                ]);

                $event->menu->add([
                  'text' => trans_choice('league.schedule', 2),
                  'icon' => 'fa fa-calendar',
                  'icon_color' => 'green',
                  'submenu' => [
                    [
                      'text' => __('Manage'),
                      'url'  => route('schedule.index', app()->getLocale()),
                      'icon' => 'fas fa-calendar-plus',
                      'can' => 'manage-schedules',
                    ],[
                      'text' => __('Calendar'),
                      'url'  => route('schedule_event.cal', app()->getLocale()),
                      'icon' => 'fas fa-calendar-alt',
                      'can'  => 'ro-schedules',
                    ],[
                      'text' => __('Compare'),
                      'url'  => route('schedule.index_piv', app()->getLocale()),
                      'icon' => 'fas fa-calendar-week',
                      'can'  => 'ro-schedules',
                    ]
                  ]
                ]);

                $league_files =  ( Auth::user()->league_filecount > 0 ) ? Auth::user()->league_filecount : 0;
                $club_files =  ( Auth::user()->club_filecount > 0 ) ? Auth::user()->club_filecount : 0;
                $all_files = $league_files + $club_files;

                if ( $all_files > 0 ){
                  $event->menu->add([
                    'text' => 'Downloads',
                    'topnav_right' => true,
                    'route' => ['user_archive.get', ['user' => Auth::user()->id]],
                    'label'       => $all_files,
                    'label_color' => 'info',
                    'can'  => ['ro-clubs','ro-leagues'],
                  ]);
                };

                $event->menu->add([
                  'text' => __('season').' '.config('global.season'),
                  'topnav' => true,
                  'route' => ['home', ['language' => app()->getLocale()]],
                ]);


                $regionmenu = array();
                $regionmenu['text'] = session('cur_region',Auth::user()->region)->name;
                $regionmenu['icon'] = 'fas fa-globe-europe';
                $regionmenu['topnav_right'] = true;

                $regions = Region::all();

                foreach ( $regions as $r ){
                  $rs['text'] = $r->name;
                  $rs['url'] = route('region.set',['region' => $r->id]);
                  $regionmenu['submenu'][] = $rs;
                }

                $event->menu->add($regionmenu);

                if (App::getLocale() == 'en') {
                    $icon_locale = 'gb';
                } else {
                    $icon_locale = App::getLocale();
                };

                $event->menu->add([
                  'icon' => 'flag-icon flag-icon-'.$icon_locale,
                  'text' => strtoupper(App::getLocale()),
                  'topnav_right' => true,
                  'submenu' => [
                    [
                      'text'  => __('english'),
                      'icon'  => 'flag-icon flag-icon-gb',
                      'url' => route(Route::currentRouteName(), array_merge(Route::current()->parameters(), ['language' => 'en'])),
                    ],
                    [
                      'text'  => __('deutsch'),
                      'icon'  => 'flag-icon flag-icon-de',
                      'url' => route(Route::currentRouteName(), array_merge(Route::current()->parameters(), ['language' => 'de'])),
                    ]
                  ]
                ]);

                $event->menu->add([
                  'icon' => 'fas fa-paperclip',
                  'icon-color' => 'dark',
                  'text' => __('Administration'),
                  'submenu' => [
                    [
                      'text'  => __('Approve Users'),
                      'icon'  => 'fas fa-thumbs-up',
                      'url' => route('admin.user.index.new', app()->getLocale()),
                      'can'  => 'manage-users',
                    ],
                    [
                      'text'  => __('Manage Users'),
                      'icon'  => 'fas fa-users',
                      'url' => route('admin.user.index', app()->getLocale()),
                      'can'  => 'manage-users',
                    ],
                    [
                    'text'  => __('Manage Members'),
                    'icon'  => 'fas fa-users',
                    'url' => route('member.index', app()->getLocale()),
                    'can'  => 'manage-members',
                    ],
                    [
                      'text'  => __('Audit Trail'),
                      'icon'  => 'fas fa-stream',
                      'url' => route('admin.audit.index', app()->getLocale()),
                    ],
                    [
                      'text'  => __('message.menu.list'),
                      'icon'  => 'fas fa-envelope',
                      'url' => route('message.index', app()->getLocale()),
                    ],
                    [
                      'text'  => __('Settings'),
                      'icon'  => 'fas fa-cog',
                      'url' => route('region.edit', ['language'=>app()->getLocale(),'region'=>session('cur_region',Auth::user()->region)->id]),
                      'can' => 'update-regions'
                    ],
                    [
                      'text'  => __('Regions'),
                      'icon'  => 'fas fa-globe-europe',
                      'url' => route('region.index', ['language'=>app()->getLocale()]),
                      'can' => 'manage-regions'
                    ],
                  ]
                ]);

            });
    }
}
