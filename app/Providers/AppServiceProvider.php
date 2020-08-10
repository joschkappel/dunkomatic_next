<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Events\Dispatcher;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;

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
      $events->listen(BuildingMenu::class, function (BuildingMenu $event) {

      $event->menu->add([
            'text' => trans_choice('club.club', 2),
            'icon_color' => 'orange',
            'icon' => 'fas fa-basketball-ball',
            //'can'  => 'manage-blog',
            'submenu' => [
              [
                'text' => __('club.menu.list'),
                'url'  => route('club.index', app()->getLocale()),
                'icon_color' => 'orange',
                'icon' => 'fas fa-list',
              ],
              [
                'text' => __('club.menu.stats'),
                'url' => route('club.index_stats', app()->getLocale()),
                'icon_color' => 'orange',
                'icon' => 'fas fa-chart-bar',
              ]
            ]
        ]);

        $event->menu->add([
            'text' => trans_choice('league.league', 2),
            'icon_color' => 'yellow',
            'icon' => 'fa fa-trophy',
            //'can'  => 'manage-blog',
            'submenu' => [
              [
                'text' => __('league.menu.list'),
                'url'  => route('league.index', app()->getLocale()),
                'icon_color' => 'yellow',
                'icon' => 'fas fa-list',
              ],
              [
                'text' => __('league.menu.stats'),
                'url'  => route('league.index_stats', app()->getLocale()),
                'icon_color' => 'yellow',
                'icon' => 'fas fa-chart-bar',
              ]
            ]
        ]);

        $event->menu->add([
            'text' => trans_choice('schedule.scheme',2),
            'url'  => route('scheme.index', app()->getLocale()),
            'icon' => 'fas fa-people-arrows',
            //'can'  => 'manage-blog',
        ]);

        $event->menu->add([
            'text' => trans_choice('league.schedule',2),
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
            //'can'  => 'manage-blog',
          ]);

          if (App::getLocale() == 'en'){
            $icon_locale = 'gb';
          } else {
            $icon_locale = App::getLocale();
          };

          // Log::debug('route '.Route::currentRouteName());
          // Log::debug(' param '.print_r(Route::current()->parameters(),true));
          // Log::debug('lang '.App::getLocale());

          $event->menu->add([
            'icon' => 'flag-icon flag-icon-'.$icon_locale,
            'text' => strtoupper(App::getLocale()),
            'topnav_right' => true,
            'submenu' => [
              [
                'text'  => 'english',
                'icon'  => 'flag-icon flag-icon-gb',
                'url' => route(Route::currentRouteName(), array_merge(Route::current()->parameters(), ['language' => 'en'])),
              ],
              [
                'text'  => 'deutsch',
                'icon'  => 'flag-icon flag-icon-de',
                'url' => route(Route::currentRouteName(), array_merge(Route::current()->parameters(), ['language' => 'de'])),
              ]
            ]
            ]);

      });
    }
}
