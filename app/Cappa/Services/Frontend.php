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
		if ($this->loggedInCheck()) return $this;
		// TODO -- Change this to custom exception
		throw new Exception("Session must be logged in");
	}

	public function loggedInCheck()
	{
		return $this->_auth->check();
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

// =================================================================
// === Heart Activity

	public function doesPlayerHaveHearts()
	{
		return $this->_cappaMan->doesPlayerHaveHearts($this->getPlayer());
	}

	public function canPlayerAccumulateHeart($hearts = 1)
	{
		$this->_loggedInOrFail();
		if (!$this->_cappaMan->canPlayerAccumulateHeart($hearts))
			return false;
		// Currently no other reason to deny heart accumlating
		return true;
	}

	public function playerAccumulatesHeart($hearts = 1)
	{
		$player = $this->getPlayer();
		return $this->_cappaMan->playerAccumulatesHeart($player,$hearts);
	}

// =================================================================
// === Pool Settings Activity

    public function isPlayerInPool()
    {
        return $this->_cappaMan->isPlayerInPool($this->getPlayer());
    }

    public function canPlayerChangePoolShare($sharePercentage)
    {
		$player = $this->getPlayer();
        return $this->_cappaMan->canPlayerChangePoolShare($player, $sharePercentage);
    }

	public function playerChangesPoolShare($sharePercentage)
	{
		$player = $this->getPlayer();
        return $this->_cappaMan->playerChangesPoolShare($player, $sharePercentage);
	}

// =================================================================
// === Transactions

	public function canPlayerGiveHeartTo($receivingPlayer)
	{
		$player = $this->getPlayer();
		return $this->_cappaMan->canPlayerGiveHeartTo($player, $receivingPlayer);
	}

	public function playerGivesHeartTo($receivingPlayer)
	{
		$player = $this->getPlayer();
		return $this->_cappaMan->playerGivesHeartTo($player, $receivingPlayer);
	}

}
