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
		$otherPlayers = CappaMan::getAllOtherPlayers();

		return \View::make('cappa.dashboard', array(
			'player'=>$this->_getPlayer(),
			'otherPlayers'=>$otherPlayers,
		));
	}

	public function getAddHeart()
	{
		$player = $this->_getPlayer();
		CappaMan::playerAccumulatesHeart($player);
		return \Redirect::route('cappa.dashboard')
			->with('flash_notice', 'You have added a heart!');
	}

	public function getGiveHeart($receivingPlayerId)
	{
		$receivingPlayer = Player::find($receivingPlayerId);
		try {
			CappaMan::playerGivesHeartTo($receivingPlayerId);
		} catch (\Exception $e) {
			return \Redirect::route('cappa.dashboard')
				->with('flash_notice', $e->getMessage());
		}
		return \Redirect::route('cappa.dashboard')
			->with('flash_notice', 'You have given a heart to '.$receivingPlayer->username);
	}

}
