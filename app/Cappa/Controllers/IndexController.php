<?php namespace Cappa\Controllers;

use \CappaMan;
use Cappa\Entities\Player;

class IndexController extends \Cappa\GenePool\Controller\Root {

	public function __construct()
	{

		$this->beforeFilter('auth');

/*
		$this->beforeFilter('guest', array('only' => array(
			'getLogin',
			'postLogin',
		)));
*/
	}

	protected function _getPlayer()
	{
		return CappaMan::getPlayer();
	}

	public function getIndex()
	{
		return \View::make('cappa.dashboard',
			array('player'=>$this->_getPlayer())
		);
	}

	public function getAddPoint()
	{
		$player = $this->_getPlayer();
		CappaMan::playerAccumulatesPoint($player);
		return \Redirect::route('cappa.dashboard')
			->with('flash_notice', 'You have added a point!');
	}




}
