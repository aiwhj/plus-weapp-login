<?php

namespace Aiwhj\WeappLogin\Providers;

use Zhiyi\Plus\Support\PackageHandler;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Boorstrap the service provider.
     *
     * @return void
     */
    public function boot()
    {
        // Register a database migration path.
        $this->loadMigrationsFrom($this->app->make('path.weapp-login.migrations'));

        // Register translations.
        $this->loadTranslationsFrom($this->app->make('path.weapp-login.lang'), 'weapp-login');

        // Register view namespace.
        $this->loadViewsFrom($this->app->make('path.weapp-login.views'), 'weapp-login');

        // Publish public resource.
        $this->publishes([
            $this->app->make('path.weapp-login.assets') => $this->app->publicPath().'/assets/weapp-login',
        ], 'weapp-login-public');

        // Publish config.
        $this->publishes([
            $this->app->make('path.weapp-login.config').'/weapp-login.php' => $this->app->configPath('weapp-login.php'),
        ], 'weapp-login-config');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // Bind all of the package paths in the container.
        $this->bindPathsInContainer();

        // Merge config.
        $this->mergeConfigFrom(
            $this->app->make('path.weapp-login.config').'/weapp-login.php',
            'weapp-login'
        );

        // register cntainer aliases
        $this->registerCoreContainerAliases();

        // Register singletons.
        $this->registerSingletions();

        // Register Plus package handlers.
        $this->registerPackageHandlers();
    }

    /**
     * Bind paths in container.
     *
     * @return void
     */
    protected function bindPathsInContainer()
    {
        foreach ([
            'path.weapp-login' => $root = dirname(dirname(__DIR__)),
            'path.weapp-login.assets' => $root.'/assets',
            'path.weapp-login.config' => $root.'/config',
            'path.weapp-login.database' => $database = $root.'/database',
            'path.weapp-login.resources' => $resources = $root.'/resources',
            'path.weapp-login.lang' => $resources.'/lang',
            'path.weapp-login.views' => $resources.'/views',
            'path.weapp-login.migrations' => $database.'/migrations',
            'path.weapp-login.seeds' => $database.'/seeds',
        ] as $abstract => $instance) {
            $this->app->instance($abstract, $instance);
        }
    }

    /**
     * Register singletons.
     *
     * @return void
     */
    protected function registerSingletions()
    {
        // Owner handler.
        $this->app->singleton('weapp-login:handler', function () {
            return new \Aiwhj\WeappLogin\Handlers\PackageHandler();
        });

        // Develop handler.
        $this->app->singleton('weapp-login:dev-handler', function ($app) {
            return new \Aiwhj\WeappLogin\Handlers\DevPackageHandler($app);
        });
    }

    /**
     * Register the package class aliases in the container.
     *
     * @return void
     */
    protected function registerCoreContainerAliases()
    {
        foreach ([
            'weapp-login:handler' => [
                \Aiwhj\WeappLogin\Handlers\PackageHandler::class,
            ],
            'weapp-login:dev-handler' => [
                \Aiwhj\WeappLogin\Handlers\DevPackageHandler::class,
            ],
        ] as $abstract => $aliases) {
            foreach ($aliases as $alias) {
                $this->app->alias($abstract, $alias);
            }
        }
    }

    /**
     * Register Plus package handlers.
     *
     * @return void
     */
    protected function registerPackageHandlers()
    {
        $this->loadHandleFrom('weapp-login', 'weapp-login:handler');
        $this->loadHandleFrom('weapp-login-dev', 'weapp-login:dev-handler');
    }

    /**
     * Register handler.
     *
     * @param string $name
     * @param \Zhiyi\Plus\Support\PackageHandler|string $handler
     * @return void
     */
    private function loadHandleFrom(string $name, $handler)
    {
        PackageHandler::loadHandleFrom($name, $handler);
    }
}
