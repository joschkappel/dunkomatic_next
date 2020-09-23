<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Events\Dispatcher;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;

use App\Club;
use App\League;
use App\Setting;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Dispatcher $events)
    {

       // if (\Schema::hasTable('settings')){
       //  config([
       //    'global' => Setting::all([
       //        'name','value'
       //    ])
       //    ->keyBy('name') // key every setting by its name
       //    ->transform(function ($setting) {
       //         return $setting->value; // return only the value
       //    })
       //    ->toArray() // make it an array
       //  ]);
       // };

        $events->listen(BuildingMenu::class, function (BuildingMenu $event) {
            $clubmenu = array();
            $clubmenu['text'] = trans_choice('club.club', 2);
            $clubmenu['icon_color'] = 'orange';
            $clubmenu['icon'] = 'fas fa-basketball-ball';

            if (Auth::user()->regionadmin) {
                $smenu['text'] = __('club.menu.list');
                $smenu['url']  = route('club.index', app()->getLocale());
                $smenu['icon_color'] = 'orange';
                $smenu['icon'] =  'fas fa-list';
                $clubmenu['submenu'][] = $smenu;
            } else {
                foreach (Auth::user()->clubs as $c) {
                    $smenu['text'] = $c->shortname;
                    $smenu['url']  = route('club.dashboard', ['language'=>app()->getLocale(), 'id'=>$c->id ]);
                    $smenu['icon_color'] = 'orange';
                    $smenu['icon'] =  'fas fa-list';
                    $clubmenu['submenu'][] = $smenu;
                }
            }

            $smenu['text'] = __('club.menu.stats');
            $smenu['url']  = route('club.index_stats', app()->getLocale());
            $smenu['icon_color'] = 'orange';
            $smenu['icon'] =  'fas fa-chart-bar';
            $clubmenu['submenu'][] = $smenu;
            $event->menu->add($clubmenu);


            $leaguemenu = array();
            $leaguemenu['text'] = trans_choice('league.league', 2);
            $leaguemenu['icon_color'] = 'yellow';
            $leaguemenu['icon'] = 'fas fa-trophy';

            if (Auth::user()->regionadmin) {
                $smenu['text'] = __('league.menu.list');
                $smenu['url']  = route('league.index', app()->getLocale());
                $smenu['icon_color'] = 'yellow';
                $smenu['icon'] =  'fas fa-list';
                $leaguemenu['submenu'][] = $smenu;
            } else {
                foreach (Auth::user()->leagues as $l) {
                    $smenu['text'] = $l->shortname;
                    $smenu['url']  = route('league.dashboard', ['language'=>app()->getLocale(), 'id'=>$l->id ]);
                    $smenu['icon_color'] = 'yellow';
                    $smenu['icon'] =  'fas fa-list';
                    $leaguemenu['submenu'][] = $smenu;
                }
            }

            $smenu['text'] = __('league.menu.stats');
            $smenu['url']  = route('league.index_stats', app()->getLocale());
            $smenu['icon_color'] = 'yellow';
            $smenu['icon'] =  'fas fa-chart-bar';
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
                ],[
                  'text' => __('Calendar'),
                  'url'  => route('schedule_event.cal', app()->getLocale()),
                  'icon' => 'fas fa-calendar-alt',
                ],[
                  'text' => __('Compare'),
                  'url'  => route('schedule.index_piv', app()->getLocale()),
                  'icon' => 'fas fa-calendar-week',
                ]
              ]
            ]);

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
              'can'  => 'list-users',
              'submenu' => [
                [
                  'text'  => __('Approve Users'),
                  'icon'  => 'fas fa-thumbs-up',
                  'url' => route('admin.user.index.new', app()->getLocale()),
                ],
                [
                  'text'  => __('Manage Users'),
                  'icon'  => 'fas fa-users',
                  'url' => route('admin.user.index', app()->getLocale()),
                ],
                [
                  'text'  => __('Audit Trail'),
                  'icon'  => 'fas fa-stream',
                  'url' => route('admin.audit.index', app()->getLocale()),
                ],
              ]
            ]);
        });
    }
}
