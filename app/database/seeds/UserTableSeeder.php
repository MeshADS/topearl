<?php

// Composer: "fzaninotto/faker": "v1.3.0"
use Faker\Factory as Faker;

class UserTableSeeder extends Seeder {

	public function run()
	{
		$faker = Faker::create();

		try
		{
		    // Create the user
		    $user = Sentry::createUser(array(
		        'email'     => 'info@topearl.com',
		        'password'  => 'welcome1',
		        'activated' => true,
		    ));

		    // Let's get the activation code
    		$activationCode = $user->getActivationCode();

		    // Find the group using the group id
		    $adminGroup = Sentry::findGroupByName('Administrators');

		    // Assign the group to the user
		    $user->addGroup($adminGroup);
		}
		catch (\Cartalyst\Sentry\Users\UserExistsException $e)
		{
		    return Response::json('User with this login already exists.', 400);
		}
		catch (\Cartalyst\Sentry\Groups\GroupNotFoundException $e)
		{
			return Response::json('Group was not found.', 404);
		}
	}

}