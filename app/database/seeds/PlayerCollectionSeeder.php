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
		foreach($users as $user)
			Cappa\Entities\Player::createFromUser($user);
	}

}
