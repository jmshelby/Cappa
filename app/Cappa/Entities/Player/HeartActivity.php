<?php namespace Cappa\Entities\Player;

class HeartActivity extends \Cappa\GenePool\Models\Mongo\Root {

	protected $table = 'player_heart_activity';

	protected $fillable = array(
	);

    public function player()
    {
        return $this->belongsTo('Cappa\Entities\Player');
    }

}
