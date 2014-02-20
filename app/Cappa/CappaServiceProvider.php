<?php namespace Cappa;

use Cappa\Services\Manager;

use Route;

class CappaServiceProvider extends \Illuminate\Support\ServiceProvider {

	public function boot()
	{

		// Routes

		Route::controller('cappa','\Cappa\Controllers\IndexController',array(
    		'getIndex' => 'cappa.dashboard',
    		'getAddHeart' => 'cappa.addHeart',
    		'getGiveHeart' => 'cappa.giveHeart',
		));

/*
		Route::controller('cappa\player','\Cappa\Controllers\PlayerController',array(
    		//'getIndex' => 'user.login',
		));
*/

	}

	public function register()
	{
		$this->app->bindShared('cappa.manager', function($app)
		{
			return new Manager();
		});
	}

}
