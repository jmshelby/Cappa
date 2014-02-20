<?php namespace Cappa\Entities;


class Player extends \Cappa\GenePool\Models\Mongo\Root {

	protected $table = 'players';

	protected $hidden = array();
	protected $fillable = array(
		'username',
		'current_hearts',
		'money_current',
		'global_dividend_rate',
	);

    public function user()
    {
        return $this->belongsTo('User');
    }

	public static function getFromUser($userId)
	{
		if (is_object($userId))
			$userId = $userId->id;
		return static::where('user_id',$userId)->first();
	}

	public static function createFromUser($user)
	{
		if ($player = static::getFromUser($user))
			return $player;
        $player = new static;
        $player->user()->associate($user);
        $player->username = $user->username;
        $player->current_hearts = 0;
        $player->current_dollars = 0.0;
        $player->share_factor = 0.0;
        $player->save();
        return $player;
	}

}
