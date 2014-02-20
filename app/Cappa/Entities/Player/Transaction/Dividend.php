<?php namespace Cappa\Entities\Player\Transaction;

class Divendend extends \Cappa\GenePool\Models\Mongo\Root {

	protected $table = 'player_transaction_dividend';

	protected $fillable = array(
	);

    public function transaction()
    {
        return $this->belongsTo('Cappa\Entities\Player\Transaction');
    }

}
