<?php

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Moloquent::unguard();
		//$this->call('UserTableSeeder');
		$this->call('UserCollectionSeeder');
		$this->call('PlayerCollectionSeeder');
	}

}
