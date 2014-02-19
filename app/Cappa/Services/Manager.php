<?php namespace Cappa\Services;

class Manager {

	protected $_dispatch;

	public function __construct(Dispatch $dispatch)
	{
		$this->_dispatch = $dispatch;
	}

	public function getPlayer()
	{
		return $this->_dispatch->getPlayer();
	}

	public function playerAccumulatesPoint($player,$points = 1)
	{
		$player->increment('current_points',$points);
	}



}
