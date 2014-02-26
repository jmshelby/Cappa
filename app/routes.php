<?php

use Carbon\Carbon;
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', array('as' => 'home', function () {
\Log::info("Logging some new stuff");
	return View::make('home');
 }));

Route::controller('user','UserController',array(
	'getLogin' => 'user.login',
	'postLogin' => 'user.login.post',
	'getLogout' => 'user.logout',
	'getProfile' => 'user.profile',
));



