<?php

// Composer: "fzaninotto/faker": "v1.3.0"
use Faker\Factory as Faker;
use Ao\Data\Models\Basicdata;

class BasicDataTableSeeder extends Seeder {

	public function run()
	{
		$faker = Faker::create();

		Basicdata::create([
			"shortname" => "Topearl", 
			"fullname" => "Topearl Catering School", 
			"logo" => "", 
			"logo_2x" => "", 
			"logo_sm" => "", 
			"logo_white" => "", 
			"logo_white_2x" => "", 
			"logo_white_sm" => "", 
			"description" => ""
			]);
	}

}