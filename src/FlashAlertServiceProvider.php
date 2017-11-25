<?php namespace Webspilka\FlashAlert;

use Illuminate\Support\ServiceProvider;

class FlashAlertServiceProvider extends ServiceProvider {

	 /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/config.php' => config_path('flashalert.php'),
        ], 'config');
    }
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(FlashAlert::class, function ($app) {
            return new FlashAlert($app['session'], $app['config']);
        });

        // $this->app->alias(FlashAlert::class, 'flashalert');
        // $this->app->alias(FlashAlert::class, 'FlashAlert');
    }
    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        // return ['flashalert'];
        // return ['flashalert', FlashAlert::class];
        return [FlashAlert::class];
    }

}
