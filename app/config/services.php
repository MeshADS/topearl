<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| Third Party Services
	|--------------------------------------------------------------------------
	|
	| This file is for storing the credentials for third party services such
	| as Stripe, Mailgun, Mandrill, and others. This file provides a sane
	| default location for this type of information, allowing packages
	| to have a conventional place to find your various credentials.
	|
	*/

	'mailgun' => array(
		'domain' => '',
		'secret' => '',
	),

	'mandrill' => array(
		'secret' => '',
	),

	'stripe' => array(
		'model'  => 'User',
		'secret' => '',
	),

	'flickr' => array(
		"key"=>"9d2e8c5dd87e7d18154f80cbbed05511",
		"secret" => "29e2c5ccfdae5c20",
		"client_id" => "134478948@N02",
	),

	'bbnsms' => array(
		"app_id"=>"9d2e8c5dd87e7d18154f80cbbed05511",
		"username" => "29e2c5ccfdae5c20",
		"password" => "134478948@N02",
	),

);
