<?php

namespace App\Providers;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use App\Events\BuildingMenu;
use App\Helpers\ViewComposer;
use App\Menu;

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
        // $this->loadViews();
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
        $viewsPath = $this->packagePath('resources/views');
        $this->loadViewsFrom($viewsPath, 'adminlte');
    }

    /**
     * Load the package translations.
     *
     * @return void
     */
    private function loadTranslations()
    {
        $translationsPath = $this->packagePath('resources/lang');
        $this->loadTranslationsFrom($translationsPath, 'adminlte');
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
     * Register the package's artisan commands.
     *
     * @return void
     */
    private function registerCommands()
    {
        $this->commands([
            AdminLteInstallCommand::class,
            AdminLteStatusCommand::class,
            AdminLteUpdateCommand::class,
            AdminLtePluginCommand::class,
        ]);
    }

    /**
     * Register the package's view composers.
     *
     * @return void
     */
    private function registerViewComposers(Factory $view)
    {
        $view->composer('page', ViewComposer::class);
    }

    /**
     * Register the menu events handlers.
     *
     * @return void
     */
    private static function registerMenu(Dispatcher $events, Repository $config)
    {
        // Register a handler for the BuildingMenu event, this handler will add
        // the menu defined on the config file to the menu builder instance.

        $events->listen(
            BuildingMenu::class,
            function (BuildingMenu $event) use ($config) {
                $menu = $config->get('menu', []);
                $menu = is_array($menu) ? $menu : [];
                $event->menu->add(...$menu);
            }
        );
    }
}
