<?php

namespace Alfrasc\MatomoTracker;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

class LaravelMatomoTrackerServiceProvider extends ServiceProvider
{
    /** @var \Illuminate\Http\Request */
    protected $request;

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot(Request $request)
    {
        $this->request = $request;

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }

        // a convenient macro to access the tracking data stored in session
        Request::macro('trackingData', function(string $key = null, $value = null){
            // session key
            $session_key = 'tracking_data';
            $session_key .= $key ? '.'.$key:'';

            // if value is given update - else get data
            if($value) {
                $this->session()->put($session_key,$value );
            }else{
                return $this->session()->get($session_key);
            }
        });
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/matomotracker.php', 'matomotracker');

        // Register the service the package provides.
        $this->app->singleton('laravelmatomotracker', function ($app) {
            return new LaravelMatomoTracker(
                $this->request,
                Config::get('matomotracker.idSite'),
                Config::get('matomotracker.url'),
                Config::get('matomotracker.tokenAuth')
            );
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['laravelmatomotracker'];
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole()
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__ . '/../config/matomotracker.php' => config_path('matomotracker.php'),
        ], 'matomotracker.config');

        $this->publishes([
            __DIR__ . './MatomoTrackerMiddleware.php' => base_path('app/')
        ]);
    }
}
