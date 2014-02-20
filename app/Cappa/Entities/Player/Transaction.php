<?php namespace Cappa\Entities\Player;

class Transaction extends \Cappa\GenePool\Models\Mongo\Root {

	protected $table = 'player_transaction';

	protected $fillable = array(
	);

    public function player()
    {
        return $this->belongsTo('Cappa\Entities\Player');
    }

}
