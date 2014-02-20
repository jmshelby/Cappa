<?php namespace Cappa\Entities;


class Player extends \Cappa\GenePool\Models\Mongo\Root {

	protected $table = 'players';

	protected $hidden = array();
	protected $fillable = array(
		'username',
		'current_points',
		'money_current',
		'global_dividend_rate',
	);

    public function user()
    {
        return $this->belongsTo('User');
    }


}
