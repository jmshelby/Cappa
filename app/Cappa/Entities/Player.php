<?php namespace Cappa\Entities;

class Player extends \Cappa\GenePool\Models\Mongo\Root {

	protected $table = 'players';

	protected $hidden = array();
	protected $fillable = array(
		'username',
		'current_hearts',
		'money_current',
		'share_factor',
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

	// == Middlemen Accessors ====================================================

	public function isInPool()
	{
		return ( !is_null($this->share_factor) && $this->share_factor > 0 );
	}

	public function getPoolShare($date = null)
	{

		// If Date is specified, check pool history
		if ( !is_null($date) && ($datetime = $this->asDateTime($date)) ) {
			// Find out what the pool share was at a specific datetime
			$query = $this->poolActivity();
			$query->where('created_at', '<=', $datetime);
			$query->orderBy('created_at', 'desc');
			$value = $query->pluck('after');
			if (!is_numeric($value)) return 0.0;
			return $value;
		}

		// Make sure the person is currently in the pool
		if (!$this->isInPool()) return 0.0;

		// Return the current pool share
		return $this->share_factor; 
	}

	public function isPoolShareValueValid($percentage)
	{
		// TODO -- Any other checks??
		return ($percentage <= 1 && $percentage >= 0);
	}

	public function setPoolShare($percentage)
	{
// TODO -- Replace this exception with a custom one
		if (!$this->isPoolShareValueValid($percentage))
			throw new \Exception(__METHOD__.": Can't set pool share to: $percentage, must be a 0-1 decimal");
		$this->share_factor = $percentage * 1.0;
		return $this;
	}

}
