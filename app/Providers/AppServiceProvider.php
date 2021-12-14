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
use Illuminate\Support\Str;

use App\Models\Setting;
use App\Models\Region;
use App\Models\Club;
use App\Models\League;

use Bouncer;

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
            if (DB::connection()->getDatabaseName()) {
                // Log::info('Yes! Successfully connected to the DB: ' . DB::connection()->getDatabaseName());
                if (Schema::hasTable('settings')) {
                    config([
                        'global' => Setting::all([
                            'name', 'value'
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

            // MENU - CLUBS
            $clubmenu = array();
            $clubmenu['text'] = trans_choice('club.club', 2);
            $clubmenu['icon_color'] = 'orange';
            $clubmenu['icon'] = 'fas fa-basketball-ball';

            $smenu['text'] = __('club.menu.list');
            $smenu['url']  = route('club.index', ['language' => app()->getLocale(), 'region'=> session('cur_region')]);
            $smenu['icon_color'] = 'orange';
            $smenu['icon'] =  'fas fa-list';
            $smenu['can'] = 'view-clubs';
            $smenu['shift'] = 'ml-3';
            $clubmenu['submenu'][] = $smenu;

            if (Auth::user()->isNotAn('superadmin')){
                $allowed_clubs = Auth::user()->clubs();
                foreach ($allowed_clubs as $c) {
                    $smenu['text'] = $c->shortname;
                    if (Auth::user()->can('update-clubs')){
                        $smenu['url']  = route('club.dashboard', ['language' => app()->getLocale(), 'club' => $c]);
                    } else {
                        $smenu['url']  = route('club.briefing', ['language' => app()->getLocale(), 'club' => $c]);
                    }
                    $smenu['icon_color'] = 'orange';
                    $smenu['icon'] =  'fas fa-list';
                    $smenu['shift'] = 'ml-3';
                    unset($smenu['can']);
                    $clubmenu['submenu'][] = $smenu;
                };
            }

            $event->menu->add($clubmenu);

            // MENU - LEAGUES
            $leaguemenu = array();
            $leaguemenu['text'] = trans_choice('league.league', 2);
            $leaguemenu['icon_color'] = 'yellow';
            $leaguemenu['icon'] = 'fas fa-trophy';

            $smenu['text'] = __('league.menu.list');
            $smenu['url']  = route('league.index',  ['language' => app()->getLocale(), 'region'=> session('cur_region')]);
            $smenu['icon_color'] = 'yellow';
            $smenu['icon'] =  'fas fa-list';
            $smenu['can'] = 'view-leagues';
            $smenu['shift'] = 'ml-3';
            $leaguemenu['submenu'][] = $smenu;

            if (Auth::user()->isNotAn('superadmin')){
                $leagues = Auth::user()->leagues();
                foreach ($leagues as $l) {
                    if (Auth::user()->can('access', $l)){
                        $smenu['text'] = $l->shortname;
                        if (Auth::user()->can('update-leagues')){
                            $smenu['url']  = route('league.dashboard', ['language' => app()->getLocale(), 'league' => $l]);
                        } else {
                            $smenu['url']  = route('league.briefing', ['language' => app()->getLocale(), 'league' => $l]);
                        }
                        $smenu['icon_color'] = 'yellow';
                        $smenu['icon'] =  'fas fa-list';
                        $smenu['shift'] = 'ml-3';
                        unset($smenu['can']);
                        $leaguemenu['submenu'][] = $smenu;
                    }
                };
            }

            $smenu['text'] =  __('league.menu.manage');;
            $smenu['url']  = route('league.index_mgmt',['language' => app()->getLocale(), 'region'=> session('cur_region')]);
            $smenu['icon_color'] = 'yellow';
            $smenu['icon'] =  'fas fa-chart-bar';
            $smenu['can'] = ['create-leagues','update-leagues'];
            $smenu['shift'] = 'ml-3';
            $leaguemenu['submenu'][] = $smenu;

            // SUBMENU - SCHEDULES
            $smenu['text'] = trans_choice('league.schedule', 2);
            $smenu['icon'] = 'fa fa-calendar';
            $smenu['icon_color'] = 'green';
            $smenu['shift'] = 'ml-3';
            unset($smenu['url']);
            unset($smenu['can']);
            unset($smenu['shift']);
            $smenu['submenu'] = [
                    [
                        'text' => __('Manage'),
                        'url'  => route('schedule.index',['language'=>app()->getLocale(), 'region'=> session('cur_region') ]),
                        'icon' => 'fas fa-calendar-plus',
                        'icon_color' => 'green',
                        'can' => 'view-schedules',
                        'shift' => 'ml-5'
                    ], [
                        'text' => __('Calendar'),
                        'url'  => route('schedule_event.cal', app()->getLocale()),
                        'icon' => 'fas fa-calendar-alt',
                        'icon_color' => 'green',
                        'can'  => 'view-schedules',
                        'shift' => 'ml-5'
                    ], [
                        'text' => __('Compare'),
                        'url'  => route('schedule.compare', ['language'=>app()->getLocale(), 'region'=> session('cur_region') ]),
                        'icon' => 'fas fa-calendar-week',
                        'icon_color' => 'green',
                        'can'  => 'view-schedules',
                        'shift' => 'ml-5'
                    ]
                ];
            $leaguemenu['submenu'][] = $smenu;

            $event->menu->add($leaguemenu);

            $smenu = [
                'text'  => __('region.menu.list'),
                'icon'  => 'fas fa-list',
                'icon_color' => 'danger',
                'route' => ['region.index', ['language' => app()->getLocale()]],
                'can' => 'view-regions',
                'shift' => 'ml-3'
            ];
            $regionmenu[] = $smenu;

            if (Auth::user()->isNotAn('superadmin')){
                $allowed_regions = Auth::user()->regions();
                foreach ($allowed_regions as $r) {
                    $smenu['text'] = $r->code;
                    if (Auth::user()->can('update-regions')){
                        $smenu['route']  = ['region.dashboard', ['language' => app()->getLocale(), 'region' => $r]];
                    } else {
                        $smenu['route']  = ['region.dashboard', ['language' => app()->getLocale(), 'region' => $r]];
                    }
                    $smenu['icon_color'] = 'danger';
                    $smenu['icon'] =  'fas fa-list';
                    $smenu['shift'] = 'ml-3';
                    unset($smenu['can']);
                    $regionmenu[] = $smenu;
                };
            }

            $smenu = [
                'text' => trans_choice('schedule.scheme', 2),
                'route'  => ['scheme.index', ['language'=>app()->getLocale()]],
                'icon' => 'fas fa-people-arrows',
                'icon_color' => 'danger',
                'shift' => 'ml-3'
            ];
            $regionmenu[] = $smenu;


            // MENU - REGION
            $event->menu->add([
                'text' => trans_choice('region.region', 2),
                'icon' => 'fas fa-globe-europe',
                'icon_color' => 'danger',
                'submenu' => $regionmenu,
            ]);

            // MENU - ADMIN
            $event->menu->add([
                'icon' => 'fas fa-paperclip',
                'icon_color' => 'blue',
                'text' => __('Administration'),
                'classes'  => 'text-danger text-uppercase',
                'submenu' => [
                    [
                        'text'  => __('auth.title.approve'),
                        'icon'  => 'fas fa-thumbs-up',
                        'icon_color' => 'blue',
                        'route' => ['admin.user.index.new', ['language'=>app()->getLocale(),'region'=>session('cur_region')]],
                        'can'  => 'udpate-users',
                        'shift' => 'ml-3'
                    ],
                    [
                        'text'  => __('auth.title.list'),
                        'icon'  => 'fas fa-users',
                        'icon_color' => 'blue',
                        'route' => ['admin.user.index', ['language'=>app()->getLocale(),'region'=>session('cur_region')]],
                        'can'  => 'view-users',
                        'shift' => 'ml-3'
                    ],

                    [
                        'text'  => __('Audit Trail'),
                        'icon'  => 'fas fa-stream',
                        'icon_color' => 'blue',
                        'route' => ['audit.index', ['language'=>app()->getLocale(), 'region'=>session('cur_region')]],
                        'shift' => 'ml-3'
                    ],
                    [
                        'text'  => __('message.menu.list'),
                        'icon'  => 'fas fa-envelope',
                        'icon_color' => 'blue',
                        'route' => ['message.index', ['language'=>app()->getLocale(), 'region'=>session('cur_region'), 'user'=>Auth::user()]],
                        'shift' => 'ml-3'
                    ],

                ]
            ]);

            $event->menu->add([
                'text' => Str::upper(__('Season')) . ' ' . config('global.season'),
                'topnav' => true,
                'route' => ['home', ['language' => app()->getLocale()]],
            ]);


            $regionmenu = array();
            $regionmenu['text'] = session('cur_region')->name;
            $regionmenu['icon'] = 'fas fa-globe-europe';
            $regionmenu['topnav_right'] = true;

            $regions = Region::all();

            foreach ($regions as $r) {
                if (Auth::user()->can('access', $r)) {
                    $rs['text'] = $r->name;
                    // $rs['url'] = route('region.set', ['region' => $r->id]);
                    $rs['url'] = route(Route::currentRouteName(), array_merge(Route::current()->parameters(), ['new_region' => $r,'region' => $r])  );
                    $regionmenu['submenu'][] = $rs;
                }
            }

            $event->menu->add($regionmenu);

            if (App::getLocale() == 'en') {
                $icon_locale = 'gb';
            } else {
                $icon_locale = App::getLocale();
            };

            $event->menu->add([
                'icon' => 'flag-icon flag-icon-' . $icon_locale,
                'text' => strtoupper(App::getLocale()),
                'topnav_right' => true,
                'submenu' => [
                    [
                        'text'  => __('english'),
                        'icon'  => 'flag-icon flag-icon-gb',
                        'url' => route(Route::currentRouteName(), array_merge(Route::current()->parameters(), ['language' => 'en'])  ),
                    ],
                    [
                        'text'  => __('deutsch'),
                        'icon'  => 'flag-icon flag-icon-de',
                        'url' => route(Route::currentRouteName(), array_merge(Route::current()->parameters(), ['language' => 'de'])),
                    ]
                ]
            ]);


        });
    }
}
