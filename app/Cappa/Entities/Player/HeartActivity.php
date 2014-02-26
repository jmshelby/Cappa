<?php namespace Cappa\Entities\Player;

class HeartActivity extends \Cappa\GenePool\Models\Mongo\Root {

	protected $table = 'player_heart_activity';

	protected $fillable = array(
		'amount_acquired',
		'activity_type', // TODO -- need to define this behavior later
	);

    public function player()
    {
        return $this->belongsTo('Cappa\Entities\Player');
    }

    public static function newFromAcquire($player, $hearts)
    {
        $activity = new static;
        $activity->amount_acquired = $hearts;
        $activity->player()->associate($player);
        $activity->save();
        return $activity;
    }

}
