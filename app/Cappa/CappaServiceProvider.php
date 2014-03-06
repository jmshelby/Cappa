<?php namespace Cappa;

use Cappa\Services\Frontend;
use Route;

class CappaServiceProvider extends \Illuminate\Support\ServiceProvider {

	public function boot()
	{

		// Routes

		Route::controller('cappa','\Cappa\Controllers\IndexController',array(
    		'getIndex'				=> 'cappa.dashboard',
    		'getAddHeart'			=> 'cappa.addHeart',
    		'postChangePoolShare'	=> 'cappa.changePoolShare',
    		'getGiveHeart'			=> 'cappa.giveHeart',
    		'getTransactionHistory'	=> 'cappa.transactionHistory',
		));

		Route::controller('cappa-cron','\Cappa\Controllers\CronController');

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
			return new CappaManager();
		});
		$this->app->bindShared('cappa.service.frontend', function($app)
		{
			return new Frontend($app['cappa.manager'], $app['auth']);
		});
	}

}
