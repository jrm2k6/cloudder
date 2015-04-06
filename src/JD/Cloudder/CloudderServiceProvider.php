<?php namespace JD\Cloudder;

use Illuminate\Support\ServiceProvider;
use Cloudinary;

class CloudderServiceProvider extends ServiceProvider {

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
		$this->package('jd/cloudder');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app['cloudinary'] = $this->app->share(function($app)
		{
			return new CloudinaryWrapper($app['config'], new Cloudinary, new Cloudinary\Uploader);
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('cloudinary');
	}

}