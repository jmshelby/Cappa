<?php namespace Cappa\Services;

use Cappa\Entities\Player;

class Manager {


	const AQUIRE_FACTOR_BASE		= 1;
	const AQUIRE_FACTOR_PERCENTAGE	= .05;

/*
	public function __construct(Dispatch $dispatch)
	{
		$this->_dispatch = $dispatch;
	}
*/

	public function getCurrentUser()
	{
		return \Auth::user();
	}

	protected $_player;
	public function getPlayer()
	{
		if (is_null($this->_player)) {
			$user = $this->getCurrentUser();
			$player = Player::where('user_id',$user->id)->first();
			if (!$player) {
				$player = new Player;
				$player->user()->associate($user);
				$player->username = $user->username;
				$player->current_points = 0;
				$player->current_dollars = 0.0;
				$player->save();
			}
			$this->_player = $player;
		}
		return $this->_player;
	}

	public function getAllOtherPlayers()
	{
		$currentPlayer = $this->getPlayer();
		$players_q = Player::where('_id', '!=', $currentPlayer->id);
		return $players_q->get();
	}

	public function playerAccumulatesPoint($player,$points = 1)
	{
		$player->increment('current_points',$points);
	}

	public function playerGivesPointTo($receivingPlayer)
	{
		$player = $this->getPlayer();

		if ($player->current_points < 1)
			throw new \Exception('No points left to give');

		if (!is_object($receivingPlayer))
			$receivingPlayer = Player::find($receivingPlayer);

		// - Players can spend Points on another player, which results in the recieving player aquiring Money
			// - The amount of Money aquired by the reciever is equal to [X percentage of the giver's amount of Money] + .01 (base factor)

		$newDollars = $this->_calculateNewDollarsFromGiver($player);
\Log::info("New dollars calculated: $newDollars");

		$player->decrement('current_points',1);
		$receivingPlayer->increment('current_dollars', $newDollars);
	}

	protected function _calculateNewDollarsFromGiver($givingPlayer)
	{
		$givingPlayerDollars = ($givingPlayer->current_dollars) ? $givingPlayer->current_dollars : 0 ;
		$givingPlayerDollars = $givingPlayerDollars * 1.0;
		return ( self::AQUIRE_FACTOR_PERCENTAGE * $givingPlayerDollars ) + self::AQUIRE_FACTOR_BASE ;
	}

}
