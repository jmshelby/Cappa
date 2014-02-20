<?php namespace Cappa\Entities\Player;

class PoolActivity extends \Cappa\GenePool\Models\Mongo\Root {

	protected $table = 'player_pool_activity';

	protected $fillable = array(
		'before',
		'after',
	);

    public function player()
    {
        return $this->belongsTo('Cappa\Entities\Player');
    }

}
