<?php

class UserCollectionSeeder extends Seeder {


	protected $_records = array(
		array(
			'firstname' => 'Maximillion',
			'lastname' => 'Vermillion',
			'username' => 'max',
			'password' => 'my_pass',
		),
		array(
			'firstname' => 'Jake',
			'lastname' => 'Shelby',
			'username' => 'jmshelby',
			'password' => 'new$7777777',
		),
		array(
			'firstname' => 'Pete',
			'lastname' => 'Schmidt',
			'username' => 'pbj',
			'password' => 'alphabetyspegetti',
		),
		array(
			'firstname' => 'Randen',
			'lastname' => 'Kelly',
			'username' => 'rkelly',
			'password' => 'new55555',
		),
	);


	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		DB::collection('users')->delete();
		foreach($this->_records as $record)
		{
			$record['password'] = Hash::make($record['password']);
			User::create($record);
		}
	}

}
