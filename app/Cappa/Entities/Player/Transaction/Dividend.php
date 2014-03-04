<?php namespace Cappa\Entities\Player\Transaction;

class Dividend extends \Cappa\GenePool\Models\Mongo\Root
{

	protected $table = 'player_transaction_dividend';

	protected $fillable = array(
		'money_received',
		'receiving_player_pool_rate',
		'scaled_dividend_rate', // Calculated percentage of pool, dividend to single person represents
		'pool_id', // Relationship for future use
	);

	public function transaction()
	{
		return $this->belongsTo('Cappa\Entities\Player\Transaction');
	}

	public function receivingPlayer()
	{   
		return $this->belongsTo('Cappa\Entities\Player','receiving_player_id');
	}

	public function donor()
	{   
		return $this->belongsTo('Cappa\Entities\Player','donor_player_id');
	}

	public function heartDonor()
	{   
		return $this->belongsTo('Cappa\Entities\Player','heart_donor_player_id');
	}

	// Pool represents a certain pool other than the global one; null means global pool
	public static function createFromPayoutToPlayer($money, $transaction, $player, $scaledPercentage, $pool = null)
	{
		$div = new static;

		$div->money_received = $money;
		$div->receiving_player_pool_rate = $player->getPoolShare($transaction->created_at);
		$div->scaled_dividend_rate = $scaledPercentage;

		$div->transaction()->associate($transaction);
		$div->receivingPlayer()->associate($player);
		$div->donor()->associate($transaction->receivingPlayer);
		$div->heartDonor()->associate($transaction->player);

		$div->save();

		return $div;
	}

}
