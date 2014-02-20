<?php namespace Cappa;

use Illuminate\Support\ServiceProvider;
use Route;

class CappaServiceProvider extends ServiceProvider {

	public function boot()
	{

		// Routes

		Route::controller('cappa','\Cappa\Controllers\IndexController',array(
    		'getIndex' => 'cappa.dashboard',
		));

/*
		Route::controller('cappa\player','\Cappa\Controllers\PlayerController',array(
    		//'getIndex' => 'user.login',
		));
*/

	}

	public function register()
	{
/*
		// The connection factory is used to create the actual connection instances on
		// the database. We will inject the factory into the manager so that it may
		// make the connections while they are actually needed and not of before.
		$this->app->bindShared('db.factory', function($app)
		{
			return new ConnectionFactory($app);
		});

		// The database manager is used to resolve various connections, since multiple
		// connections might be managed. It also implements the connection resolver
		// interface which may be used by other components requiring connections.
		$this->app->bindShared('db', function($app)
		{
			return new DatabaseManager($app, $app['db.factory']);
		});
*/
	}

}
