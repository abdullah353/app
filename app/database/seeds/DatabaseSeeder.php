<?php

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();

		// $this->call('UserTableSeeder');
		$this->call('UserTableSeeder');
    $this->command->info('User table seeded!');

    DB::table('keys')->delete();
		Key::create(array(
			"user_id"=>1,
			"devname" => "c8f80749-45f4-4acf-bcf3-08abe083ddd5",
			"appname" => "Teletaal-cba3-426f-99c7-c9d31df0cc0a",
			"cert" =>"066b83b2-7170-45ba-aa3f-7de6125cb2f7",
			"gtoken" => "AgAAAA**AQAAAA**aAAAAA**tlOuUw**nY+sHZ2PrBmdj6wVnY+sEZ2PrA2dj6AFmYWoDJCDpwidj6x9nY+seQ**Zz8CAA**AAMAAA**VLvTphMI/tOBmrN2H8rQCszU0N8kTB89crZGt/N9qFXdyLkYced3af3K/qGXmQEl0NQw6j1AR5pRaz0nkWughw4CQC+qXJagXQE1QapcwiU5JQHZCpJb2G6z1QS7eiobYwVEB+UDNbVSws7DFETeo9OcW8dcXi5nVZqQ8a5FbggIbhxl/rslusA0Rm9eI73G2g9c/3ohoCtJUD3IqRQ6zQ8srltqprTQXYAlO/em4pRHBIchu2ln+/kvcyqZj6R5mVr/oUp9rsDUSBZ+oq7grhwRYwZjmjTo/bHpmADLlU3/DkGRq8qpbKrrGVJmL0NKJlNBQS/0pkPGEw0/TfF6EUL6d4KEdTpnz1soXBNY2Wc2bGDYh7m2wMpNBst5BSTaiIAGAGA1EultP3oUJofkgxagzVGYA5ODoLh5ajJ/nJjau0W9PslgdhDGhyn5OzFEf8WtK06EpMwMc8LiIkhWENciBFscLRMtnXGqdLw1QV8E8wn/H77wPlI+ZM74t1rFN4p02luJ9Jxkp+nmHNO4zURyMP3ly6WrDhC7oGcROA3U8SKlM1FLuzSy7Qn4G7l0M+QtG9hvJMH7BYHuo7UKkYfSaSt4sP4DlBm/dho1zE8SlldO7eV1yCOfdzYFBnNVwzAdwnCGU652/o1ghp4VJlPb5+19yXJcNZCViAqTDgTdI1pAOAo7SM+MZsSVgQOBoDU9rlj00S0Ayv8YwnxwXVKImrGMesm6atS3fYYeVkCKPNrKUg58SvTuDURgdHDh",
			'complvl' => 873,
			'siteID' => 0,
			'serverUrl' => "https://api.ebay.com/ws/api.dll",
			'runame' => "",
		));
	}

}