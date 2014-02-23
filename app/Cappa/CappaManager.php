<?php namespace Cappa;

use Cappa\Entities\Player;

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
		$player->increment('current_hearts',$hearts);
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

		$newDollars = $this->_calculateNewDollarsFromGiver($player);
\Log::info("New dollars calculated: $newDollars");

		$player->decrement('current_hearts',1);
		$receivingPlayer->increment('current_dollars', $newDollars);

		return $receivingPlayer;
	}

	protected function _calculateNewDollarsFromGiver($givingPlayer)
	{
		$givingPlayerDollars = ($givingPlayer->current_dollars) ? $givingPlayer->current_dollars : 0 ;
		$givingPlayerDollars = $givingPlayerDollars * 1.0;
		return ( self::AQUIRE_FACTOR_PERCENTAGE * $givingPlayerDollars ) + self::AQUIRE_FACTOR_BASE ;
	}

}
