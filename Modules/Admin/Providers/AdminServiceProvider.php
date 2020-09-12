<?php

namespace Modules\Admin\Providers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use Nwidart\Modules\Facades\Module;
use Illuminate\Support\Arr;
use Illuminate\Routing\Router;

class AdminServiceProvider extends ServiceProvider
{
    /**
     * @var string $moduleName
     */
    protected $moduleName = 'Admin';

    /**
     * @var string $moduleNameLower
     */
    protected $moduleNameLower = 'admin';

    /**
     * The filters base class name.
     *
     * @var array
     */
    protected $middleware = [
        'Admin' => [
            'auth.admin'    => 'AdminAuthMiddleware',
            'admin.permissions'   => 'PermissionMiddleware',
            'admin'   => 'AdminRedirectIfAuthenticated',
        ],
    ];

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerMiddleware($this->app['router']);
        $this->registerViews();
        $this->registerFactories();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'Database/Migrations'));
    }

    /**
     * Register the filters.
     *
     * @param  Router $router
     * @return void
     */
    public function registerMiddleware(Router $router)
    {
        foreach ($this->middleware as $module => $middlewares)
        {
            foreach ($middlewares as $name => $middleware)
            {
                $class = "Modules\\{$module}\\Http\\Middleware\\{$middleware}";

                $router->aliasMiddleware($name, $class);
            }
        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            module_path($this->moduleName, 'Config/config.php') => config_path($this->moduleNameLower . '.php'),
        ], 'config');

        $this->mergeConfigFrom(
            module_path($this->moduleName, 'Config/config.php'), $this->moduleNameLower
        );

        //merge auth config files
        $authConfigPath = module_path($this->moduleName, 'Config/auth.php');
        $authConfigs = include($authConfigPath);

        $defaultConfigPath = config_path('auth.php');
        $defaultConfigs = include($defaultConfigPath);

        //merge configs in both files
        if(is_array($defaultConfigs) && count($defaultConfigs)>0)
        {
            foreach ($defaultConfigs as $key => $config)
            {
                if(isset($authConfigs[$key]))
                {
                    $defaultConfigs[$key] = array_merge($authConfigs[$key], $config);
                }
            }
        }

        //add new configs to default config array
        if(is_array($authConfigs) && count($authConfigs)>0)
        {
            foreach ($authConfigs as $key => $config)
            {
                if(!isset($defaultConfigs[$key]))
                {
                    $defaultConfigs[$key] = $config;
                }
            }
        }

        $menuConfigPath = module_path($this->moduleName, 'Config/menu.php');
        $menuConfigs = include($menuConfigPath);

        //update auth config file and after adding new configs
        config(["auth" => $defaultConfigs]);
        config(["menu" => $menuConfigs]);
        config(["permissions" => $menuConfigs]);
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/' . $this->moduleNameLower);

        $sourcePath = module_path($this->moduleName, 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath
        ], ['views', $this->moduleNameLower . '-module-views']);

        $this->loadViewsFrom(array_merge($this->getPublishableViewPaths(), [$sourcePath]), $this->moduleNameLower);
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/admin');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'admin');
        } else {
            $this->loadTranslationsFrom(module_path($this->moduleName, 'Resources/lang'), 'admin');
        }
    }

    /**
     * Register an additional directory of factories.
     *
     * @return void
     */
    public function registerFactories()
    {
        if (! app()->environment('production') && $this->app->runningInConsole()) {
            app(Factory::class)->load(module_path($this->moduleName, 'Database/factories'));
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }

    private function getPublishableViewPaths(): array
    {
        $paths = [];
        foreach (\Config::get('view.paths') as $path) {
            if (is_dir($path . '/modules/' . $this->moduleNameLower)) {
                $paths[] = $path . '/modules/' . $this->moduleNameLower;
            }
        }
        return $paths;
    }
}
