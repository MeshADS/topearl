<?php 

return array( 
	
	/*
	|--------------------------------------------------------------------------
	| oAuth Config
	|--------------------------------------------------------------------------
	*/

	/**
	 * Storage
	 */
	'storage' => 'Session', 

	/**
	 * Consumers
	 */
	'consumers' => array(

		/**
		 * Facebook
		 */
        'Facebook' => array(
            'client_id'     => '',
            'client_secret' => '',
            'scope'         => array(),
        ),

        'Flickr' => array(
			"key"=>"9d2e8c5dd87e7d18154f80cbbed05511",
			"secret" => "29e2c5ccfdae5c20",
			"client_id" => "131976296@N03",
		),		

	)

);