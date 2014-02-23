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
 *
 * Other Possible Fields
 *     - receiving players total money resulting
 *     - money sent to pool
 *     - giving player pool rate
 *     - receiving player pool rate
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
	);

    public function player()
    {
        return $this->belongsTo('Cappa\Entities\Player');
    }

    public function receivingPlayer()
    {
        return $this->belongsTo('Cappa\Entities\Player','receiving_player_id');
    }

    public static function newFromGiving($player, $receivingPlayer, $heartsGiven, $moneyGenerated, $moneyReceived)
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

        $trans->save();
        return $trans;
    }

}
