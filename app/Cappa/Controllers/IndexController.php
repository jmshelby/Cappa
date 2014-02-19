<?php namespace Cappa\Controllers;

use Cappa\Entities\Player;
use Cappa\Services\Dispatch;
use Cappa\Services\Manager;

use Carbon\Carbon;
use View;

class IndexController extends \BaseController {

	protected $_cappaDispatch;
	protected $_cappaManager;

	public function __construct(Dispatch $dispatch, Manager $manager)
	{

		$this->_cappaDispatch = $dispatch;
		$this->_cappaManager = $manager;

		$this->beforeFilter('auth');

/*
		$this->beforeFilter('guest', array('only' => array(
			'getLogin',
			'postLogin',
		)));
*/
	}

	public function getIndex()
	{

		$player = $this->_cappaDispatch->getPlayer();

		return View::make('cappa.dashboard',array('player'=>$player));
	}


	public function getAddPoint()
	{
		$player = $this->_cappaDispatch->getPlayer();
		$this->_cappaManager->playerAccumulatesPoint($player);
		return \Redirect::route('cappa.dashboard')
			->with('flash_notice', 'You have added a point!');
	}

}
