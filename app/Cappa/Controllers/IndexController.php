<?php namespace Cappa\Controllers;

use \App;
use \CappaMan;
use Cappa\Entities\Player;

class IndexController extends \Cappa\GenePool\Controller\Root {

	public $service;

	public function __construct()
	{
		$this->service = App::make('cappa.service.frontend');

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
		return $this->service->getPlayer();
	}

	public function getIndex()
	{
		$otherPlayers = $this->service->getAllOtherPlayers();

		return \View::make('cappa.dashboard', array(
			'player'=>$this->_getPlayer(),
			'otherPlayers'=>$otherPlayers,
		));
	}

	public function getAddHeart()
	{
		$player = $this->_getPlayer();
		$this->service->playerAccumulatesHeart($player);
		return \Redirect::route('cappa.dashboard')
			->with('flash_notice', 'You have added a heart!');
	}

	public function getGiveHeart($receivingPlayerId)
	{
		try {
			$receivingPlayer = $this->service->playerGivesHeartTo($receivingPlayerId);
		} catch (\Exception $e) {
			return \Redirect::route('cappa.dashboard')
				->with('flash_notice', $e->getMessage());
		}
		return \Redirect::route('cappa.dashboard')
			->with('flash_notice', 'You have given a heart to '.$receivingPlayer->username);
	}

}
