<?php
namespace Soda\Providers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;
use Soda;

class SodaServiceProvider extends ServiceProvider {



	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = FALSE;

	/**
	 * Perform post-registration booting of services.
	 *
	 * @return void
	 */
	public function boot() {

		$this->buildApp();

		// Loading routes
		//if (!$this->app->routesAreCached()) {
		//	//require __DIR__ . '/../routes.php';
		//}
		// Publishing configs

		$this->publishes([
			__DIR__ . '/../Config/soda.php' => config_path('soda.php'),
		]);
		// Publishing views
		$this->loadViewsFrom(__DIR__ . '/../Views', config('soda.hint_path'));

		// Loading translations
		$this->loadTranslationsFrom(__DIR__ . '/../translations', config('soda.hint_path'));

		// Publishing public assets
		$this->publishes([
			__DIR__ . '/../assets' => public_path(config('soda.hint_path')),
		], 'public');
		// Publishing migrations
		$this->publishes([
			__DIR__ . '/../migrations' => database_path('/migrations'),
		], 'migrations');
		// Publishing seeds
		$this->publishes([
			__DIR__ . '/../seeds' => database_path('/seeds'),
		], 'migrations');
	}

	/**
	 * builds common interfaces for stuff.
	 * TODO: move this somewhere sensible?
	 */
	public function buildApp() {
		$this->app->singleton('application', function ($app) {
			$application = \Soda\Models\Application::find(1);
			return $application;
			//return('bonk');
			//return new FooBar($app['SomethingElse']);
		});

		//TODO: we should prbs be defining a 'soda' singleton that has
		//access to stuff like application etc that we can call from anywhere..


	}

	/**
	 * Register bindings in the container.
	 *
	 * @return void
	 */
	public function register() {

		App::register(\Soda\Providers\UploaderProvider::class);
		App::register(\Soda\Providers\RouteServiceProvider::class);
		App::register(\Franzose\ClosureTable\ClosureTableServiceProvider::class);
		App::register(\Franzose\ClosureTable\ClosureTableServiceProvider::class);
		//$this->app->bind('Soda', Soda::class);
		App::bind('soda', function() {
			return new Soda(); //freaking cool-ass facades!
		});


		//$this->app->singleton('Soda', function(){
		//	return new Soda();
		//});
	}
}