<?php namespace Cappa;

use Cappa\Entities\Player;
use Cappa\Entities\Player\HeartActivity as PlayerHeartActivity;
use Cappa\Entities\Player\PoolActivity as PlayerPoolActivity;
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

// =================================================================
// === Heart Activity

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

// =================================================================
// === Pool Settings Activity

    public function isPlayerInPool($player)
    {
		$player = $this->player($player);
        return $player->isInPool();
    }

    public function canPlayerChangePoolShare($player, $sharePercentage)
    {
		$player = $this->player($player);
		if (!$player->isPoolShareValueValid($sharePercentage))
			return false;
        // Currently no other reason to deny pool share amounts
        return true; 
    }

	public function playerChangesPoolShare($player, $sharePercentage)
	{
		$player = $this->player($player);

		// Get old amount
		$oldAmount = $player->getPoolShare();

		// Set new amount
		$player->setPoolShare($sharePercentage);
		$player->save();

		// Create pool activity record
		$poolActivity = PlayerPoolActivity::newFromChange($player, $oldAmount, $sharePercentage);

		return $poolActivity;
	}

// =================================================================
// === Transactions

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

// TODO -- Call or Queue to distribute funds to a pool
//$poolAmount = $this->_distributeDividend($player);


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
