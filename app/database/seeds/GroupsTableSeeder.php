<?php

// Composer: "fzaninotto/faker": "v1.3.0"
use Faker\Factory as Faker;

class GroupsTableSeeder extends Seeder {

	public function run()
	{
		$faker = Faker::create();
		$names = ["Administrators",
					"Sub Administrators",
					"Students"];

		foreach(range(0, 5) as $index)
		{
			Sentry::createGroup([
				"name" => $names[$index],
				"permissions" => []
			]);
		}
	}

}