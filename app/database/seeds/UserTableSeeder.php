<?php
class UserTableSeeder extends Seeder {

	public function run()
	{
		DB::table('users')->delete();

		User::create(array(
			'firstname' => "Abdullah",
			'lastname' => "Bashir",
			'email' => "mabdullah353@gmail.com",
			'password' => Hash::make("sam353")
		));
	}

}