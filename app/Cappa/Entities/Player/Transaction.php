<?php namespace Cappa\Entities\Player;

/* Record tracking for when one player spends hearts on another player
 *
 * Fields:
 *  - hearts_given
 *
 *  - money_generated
 *  - money_received
 *
 *  - player_money_amount
 *  - player_heart_amount
 *
 *  - receiving_player_money_amount
 *
 *  - money sent to pool
 *  - receiving player pool rate
 *
 *
 * Other Possible Fields
 *     - giving player pool rate
 *
 */
class Transaction extends \Cappa\GenePool\Models\Mongo\Root {

	protected $table = 'player_transaction';

	protected $fillable = array(
		'hearts_given',
		'money_generated',
		'money_received',
		'player_money_amount',
		'player_heart_amount',
		'receiving_player_money_amount',

		'money_sent_to_pool',
		'receiving_player_pool_rate',
		'pool_divisor',

		'dividends_count',
		'dividends_paid_out_fl',
	);

    public function player()
    {
        return $this->belongsTo('Cappa\Entities\Player');
    }

    public function receivingPlayer()
    {
        return $this->belongsTo('Cappa\Entities\Player','receiving_player_id');
    }

    public function dividends()
    {
        return $this->belongsTo('Cappa\Entities\Player\Transaction\Dividend','receiving_player_id');
    }

    public static function newFromGiving($player, $receivingPlayer, $heartsGiven, $moneyGenerated, $moneyReceived, $poolDivisor, $dividendsCount)
    {

        $trans = new static;

		$trans->hearts_given = $heartsGiven;

		$trans->money_generated = $moneyGenerated;
		$trans->money_received = $moneyReceived;

		$trans->player_money_amount = $player->current_money;
		$trans->player_heart_amount = $player->current_hearts;

		$trans->receiving_player_money_amount = $receivingPlayer->current_money + $moneyReceived;

		$trans->player()->associate($player);
		$trans->receivingPlayer()->associate($receivingPlayer);

		$trans->money_sent_to_pool = $moneyGenerated - $moneyReceived;
		$trans->receiving_player_pool_rate = $receivingPlayer->share_factor;
		$trans->pool_divisor = $poolDivisor; // Sum of all player's share factors
		$trans->dividends_count = $dividendsCount;
		$trans->dividends_paid_out_fl = false;

        $trans->save();
        return $trans;
    }

}
