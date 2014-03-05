<?php

class PlayerCollectionSeeder extends Seeder {


	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		DB::collection('players')->delete();
		$users = User::all();
		foreach($users as $user) {
			$player = Cappa\Entities\Player::createFromUser($user);

			$share = mt_rand(0, 100) / 100;
			CappaMan::playerChangesPoolShare($player, $share);

		}


	}

}
