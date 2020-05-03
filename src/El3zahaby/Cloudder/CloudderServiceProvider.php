<?php

namespace JD\Cloudder;

use Illuminate\Support\ServiceProvider;
use Cloudinary;

class CloudderServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap classes for packages.
     *
     * @return void
     */
    public function boot()
    {
        $source = realpath(__DIR__.'/../../../config/cloudder.php');

        if (class_exists('Illuminate\Foundation\Application', false)) {
            $this->publishes([$source => config_path('cloudder.php')]);
        }
        $this->mergeConfigFrom($source, 'cloudder');

        $this->app['JD\Cloudder\Cloudder'] = function ($app) {
            return $app['cloudder'];
        };
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $app = $this->app;
        $this->app->singleton('cloudder', function () use ($app) {
            return new CloudinaryWrapper($app['config'], new Cloudinary, new Cloudinary\Uploader, new Cloudinary\Api);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array('cloudder');
    }
}
