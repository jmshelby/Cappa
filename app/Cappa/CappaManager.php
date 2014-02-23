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

	public function getPlayerById($id)
	{
		return Player::find($id);
	}

	public function playerAccumulatesHeart(Player $player,$hearts = 1)
	{
		$player->increment('current_hearts',$hearts);
	}

	public function playerGivesHeartTo(Player $player, $receivingPlayer)
	{
		if ($player->current_hearts < 1)
			throw new \Exception('No hearts left to give');

		if (!is_object($receivingPlayer))
			$receivingPlayer = $this->getPlayerById($receivingPlayer);

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
