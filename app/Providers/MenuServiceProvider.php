<?php

namespace App\Providers;

use App\Helpers\ViewComposer;
use App\Menu;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class MenuServiceProvider extends BaseServiceProvider
{
    /**
     * Register the package services.
     *
     * @return void
     */
    public function register()
    {
        // Bind a singleton instance of the Menu class into the service
        // container.

        $this->app->singleton(Menu::class, function (Container $app) {
            return new Menu(
                $app['config']['menu.filters'],
                $app['events'],
                $app
            );
        });
    }

    /**
     * Bootstrap the package's services.
     *
     * @return void
     */
    public function boot(Factory $view, Dispatcher $events, Repository $config)
    {
        $this->loadViews();
        // $this->loadTranslations();

        $this->loadConfig();
        // $this->registerCommands();
        $this->registerViewComposers($view);
        //$this->registerMenu($events, $config);
    }

    /**
     * Load the package views.
     *
     * @return void
     */
    private function loadViews()
    {
        $viewsPath = resource_path('views');
        $this->loadViewsFrom($viewsPath, 'layouts');
    }

    /**
     * Load the package config.
     *
     * @return void
     */
    private function loadConfig()
    {
        $this->mergeConfigFrom(config_path('menu.php'), 'menu');
    }

    /**
     * Register the package's view composers.
     *
     * @return void
     */
    private function registerViewComposers(Factory $view)
    {
        $view->composer('\App\Menu', ViewComposer::class);
    }
}
