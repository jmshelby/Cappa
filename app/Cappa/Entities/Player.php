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

	// == Relationships ==========================================================

	// One User
    public function user()
    {
        return $this->belongsTo('User');
    }

	// Many Pool Share Change Actions/Activity
	public function poolActivity()
	{
        return $this->hasMany('Cappa\Entities\Player\PoolActivity');
	}

	// Many Heart Aquisition Actions/Activity
	public function heartActivity()
	{
        return $this->hasMany('Cappa\Entities\Player\HeartActivity');
	}

	// Many Heart Transactions (giving hearts to another player)
	public function transactions()
	{
        return $this->hasMany('Cappa\Entities\Player\Transaction');
	}

	// Many Receiving Heart Transactions (receiving hearts from player)
	public function receivingTransactions()
	{
        return $this->hasMany('Cappa\Entities\Player\Transaction','receiving_player_id');
	}

	// == Factories ==============================================================

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
        $player->current_money = 0.0;
        $player->share_factor = 0.0;
        $player->save();
        return $player;
	}

}
