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
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;

use App\Models\Setting;
use App\Models\Region;

use Illuminate\Database\LazyLoadingViolationException;

use Silber\Bouncer\BouncerFacade as Bouncer;

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
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Dispatcher $events)
    {
        // ensure DB is up and accessible
        try {
            $pdo = DB::getPdo();
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

        // prepare to detect lazy loadings
        Model::preventLazyLoading( config('app.env') !== 'prod' );
        Model::handleLazyLoadingViolationUsing(function ($model, $relation) {
            $class = get_class($model);

            info("[LAZY LOADING] Attempted to lazy load [{$relation}] on model [{$class}].");

            $exception = new LazyLoadingViolationException($model, $relation);
            // dd($exception->getTraceAsString());
            if ( ( Str::contains( $exception->getTraceAsString(), 'app/Http/Controllers' ) ) or
                 ( Str::contains( $exception->getTraceAsString(), 'app/Jobs' ) ) or
                 ( Str::contains( $exception->getTraceAsString(), 'app/Observers' ) )  ){
                    info("[LAZY LOADING] [IN CONTROLLER or JOB or OBSERVER]");
                    if (config('app.env') === 'local'){
                        throw $exception;
                     } else {
                         report($exception);
                     }
            }

        });

        // create MUST have folders
        Storage::makeDirectory( config('dunkomatic.folders.backup') );
        Storage::makeDirectory( config('dunkomatic.folders.export') );

        // send ALL mails to same email account
        if ( app()->environment('staging')) {
            Mail::alwaysTo( env('MAIL_FROM_ADDRESS') );
        }

        // build menu events
        $events->listen(BuildingMenu::class, function (BuildingMenu $event) {
            // MAIN MENU REGION
            $regionmenu = [
                'text' => Str::length( session('cur_region')->name ) > 20 ? session('cur_region')->code : session('cur_region')->name,
                'icon' => 'fas fa-globe-europe',
                'icon_color' => 'danger'
            ];
            if (Auth::user()->can('update-regions')){
                $regionmenu['route']  = ['region.dashboard', ['language' => app()->getLocale(), 'region' => session('cur_region')]];
            } else {
                $regionmenu['route']  = ['region.briefing', ['language' => app()->getLocale(), 'region' => session('cur_region')]];
            }
            $event->menu->add($regionmenu);

            // MAIN MENU - CLUBS
            $clubmenu = array();
            $clubmenu['text'] = trans_choice('club.club', 2);
            $clubmenu['icon_color'] = 'orange';
            $clubmenu['icon'] = 'fas fa-basketball-ball';
            $clubmenu['url']  = route('club.index', ['language' => app()->getLocale(), 'region'=> session('cur_region')]);
            $clubmenu['can'] = 'view-clubs';

            $event->menu->add($clubmenu);

            // MENU - LEAGUES
            $leaguemenu = array();
            $leaguemenu['text'] = trans_choice('league.league', 2);
            $leaguemenu['icon_color'] = 'yellow';
            $leaguemenu['icon'] = 'fas fa-trophy';

/*             $smenu['text'] = __('league.menu.list');
            $smenu['url']  = route('league.index',  ['language' => app()->getLocale(), 'region'=> session('cur_region')]);
            $smenu['icon_color'] = 'yellow';
            $smenu['icon'] =  'fas fa-list';
            $smenu['can'] = 'view-leagues';
            $smenu['shift'] = 'ml-3';
            $leaguemenu['submenu'][] = $smenu; */


            $smenu['text'] =  __('league.menu.manage');;
            if (Auth::user()->isAn('superadmin','regionadmin','leagueadmin')) {
                $smenu['url']  = route('league.index_mgmt',['language' => app()->getLocale(), 'region'=> session('cur_region')]);
            } else {
                $smenu['url']  = route('league.index',['language' => app()->getLocale(), 'region'=> session('cur_region')]);
            }
            $smenu['icon_color'] = 'yellow';
            $smenu['icon'] =  'fas fa-chart-bar';
            $smenu['can'] = ['view-leagues'];
            $smenu['shift'] = 'ml-3';
            $leaguemenu['submenu'][] = $smenu;

            // SUBMENU - SCHEDULES
            $smenu['text'] = trans_choice('league.schedule', 2);
            $smenu['icon'] = 'fa fa-calendar';
            $smenu['icon_color'] = 'green';
            $smenu['shift'] = 'ml-3';
            unset($smenu['url']);
            unset($smenu['can']);
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
                        'shift' => 'ml-5'
                    ]
                ];
            $leaguemenu['submenu'][] = $smenu;

            $event->menu->add($leaguemenu);

            // MAIN MENU MEMBERS
            $membermenu = [
                'text' => trans_choice('role.member',2),
                'icon' => 'fas fa-users',
                'icon_color' => 'green',
                'can' => 'view-members',
                'route' => ['member.index', ['language'=>app()->getLocale(), 'region'=>session('cur_region')]],
            ];
            $event->menu->add($membermenu);

            // MENU - ADMIN
            $event->menu->add([
                'icon' => 'fas fa-paperclip',
                'icon_color' => 'blue',
                'text' => __('Administration'),
                'classes'  => 'text-danger text-uppercase',
                'submenu' => [
                    [
                        'text'  => trans_choice('region.region',2),
                        'icon'  => 'fas fa-globe-europe',
                        'icon_color' => 'blue',
                        'route' => ['region.index', ['language' => app()->getLocale()]],
                        'can' => 'update-regions',
                        'shift' => 'ml-3'
                    ],
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

            // MENU LEAGUE SCHEMAS
            $schememenu = [
                'text' => trans_choice('schedule.scheme', 2),
                'route'  => ['scheme.index', ['language'=>app()->getLocale()]],
                'icon' => 'fas fa-people-arrows',
                'icon_color' => 'danger',
            ];
            $event->menu->add($schememenu);

            $event->menu->add([
                'text' => Str::upper(__('Season')) . ' ' . config('global.season'),
                'topnav' => true,
                'route' => ['home', ['language' => app()->getLocale()]],
            ]);


            $regionmenu = array();
            $regionmenu['text'] = ' ';
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
