<?php namespace Cappa\Services;

use Cappa\CappaManager;
use Cappa\Entities\Player;
use Illuminate\Auth\AuthManager;

class Frontend {

	protected $_cappaMan;
	protected $_auth;

	public function __construct(CappaManager $cappaMan, AuthManager $auth)
	{
		$this->_cappaMan = $cappaMan;
		$this->_auth = $auth;
	}

	protected function _loggedInOrFail()
	{
		if ($this->_auth->check()) return $this;
		// TODO -- Change this to custom exception
		throw new Exception("Session must be logged in");
	}

	public function getUser()
	{
		$this->_loggedInOrFail();
		return $this->_auth->user();
	}

	protected $_player;
	public function getPlayer()
	{
		if (is_null($this->_player)) {
			$this->_player = Player::createFromUser($this->getUser());
		}
		return $this->_player;
	}

	public function getAllOtherPlayers()
	{
		$currentPlayer = $this->getPlayer();
		$players_q = Player::where('_id', '!=', $currentPlayer->id);
		return $players_q->get();
	}

	public function playerAccumulatesHeart($player,$hearts = 1)
	{
		$player = $this->getPlayer();
		return $this->_cappaMan->playerAccumulatesHeart($player,$hearts);
	}

	public function playerGivesHeartTo($receivingPlayer)
	{
		$player = $this->getPlayer();
		return $this->_cappaMan->playerGivesHeartTo($player, $receivingPlayer);
	}

}
