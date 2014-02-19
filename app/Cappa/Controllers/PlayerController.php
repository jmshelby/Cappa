<?php namespace Cappa\Controllers;

use Carbon\Carbon;

class PlayerController extends \BaseController {

	public function __construct()
	{
/*
		$this->beforeFilter('auth', array('only' => array(
			'getLogout',
			'getProfile',
		)));
		$this->beforeFilter('guest', array('only' => array(
			'getLogin',
			'postLogin',
		)));
*/
	}

	public function getIndex()
	{
		return View::make('cappa.dashboard');
	}

}
