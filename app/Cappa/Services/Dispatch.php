<?php namespace Cappa\Services;

use Cappa\Entities\Player;

class Dispatch {


	public function getCurrentUser()
	{
		return \Auth::user();
	}

	public function getPlayer()
	{
		$user = $this->getCurrentUser();
		$player = Player::where('user_id',$user->id)->first();
		if (!$player) {
			$player = new Player;
			$player->user()->associate($user);
			$player->current_points = 0;
			$player->save();
		}
		return $player;
	}


}
