<?php namespace Cappa;

use Cappa\Entities\Player;
use Cappa\Entities\Player\HeartActivity as PlayerHeartActivity;
use Cappa\Entities\Player\Transaction as PlayerTransaction;

class CappaManager {


	const AQUIRE_FACTOR_BASE		= 1;
	const AQUIRE_FACTOR_PERCENTAGE	= .05;

/*
	public function __construct(Dispatch $dispatch)
	{
		$this->_dispatch = $dispatch;
	}
*/

	public function player($sourceIdentifier, $graceful=false)
	{
		$player = null;

		if ( $sourceIdentifier instanceof Player )
			return $sourceIdentifier;

		if (!is_object($sourceIdentifier))
			$player = $this->getPlayerById($sourceIdentifier);

		if (!$player && !$graceful) {
			// TODO -- Make custom exception for this
			throw new Exception("Cannot fetch unknown player ( TODO - Make custom exception for this)");
		}

		return $player;
	}

	public function getPlayerById($id)
	{
		return Player::find($id);
	}

    public function doesPlayerHaveHearts($player)
    {
		$player = $this->player($player);
        return ($player->current_hearts > 0);
    }

    public function canPlayerAccumulateHeart($hearts = 1)
    {
        // Currently no other reason to deny heart accumlating
        return true; 
    }

	public function playerAccumulatesHeart($player, $hearts = 1)
	{
		$player = $this->player($player);

		// Add amount to player model
		$player->increment('current_hearts',$hearts);

		// Create heart activity record
		$heartActivity = PlayerHeartActivity::newFromAcquire($player,$hearts);
	
		return $heartActivity;
	}

    public function canPlayerGiveHeartTo($player, $receivingPlayer)
    {
		$player = $this->player($player);
		$receivingPlayer = $this->player($receivingPlayer);
        if (!$this->doesPlayerHaveHearts($player))
			return false;
		return true;
    }

	public function playerGivesHeartTo($player, $receivingPlayer)
	{
		$player = $this->player($player);
		$receivingPlayer = $this->player($receivingPlayer);

		// TODO - add custom exception class for this case
		if (!$this->doesPlayerHaveHearts($player))
			throw new \Exception('No hearts left to give');

		$newMoney = $this->_calculateNewMoneyFromGiver($player);
\Log::info("New Money calculated: $newMoney");

		// Create New Transaction
		$trans = PlayerTransaction::newFromGiving(
			$player,
			$receivingPlayer,
			1,
			$newMoney,
			$newMoney // This will soon be the money generated minus the donation
		);

		// Update Player Numbers
		$player->decrement('current_hearts',1);
		$receivingPlayer->increment('current_money', $newMoney);

		return $receivingPlayer;
	}

	protected function _calculateNewMoneyFromGiver($givingPlayer)
	{
		$givingPlayerMoney = ($givingPlayer->current_money) ? $givingPlayer->current_money : 0 ;
		$givingPlayerMoney = $givingPlayerMoney * 1.0;
		return ( self::AQUIRE_FACTOR_PERCENTAGE * $givingPlayerMoney ) + self::AQUIRE_FACTOR_BASE ;
	}

}
