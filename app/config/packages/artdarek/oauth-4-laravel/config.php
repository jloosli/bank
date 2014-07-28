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
            'client_id'     => $_ENV['OAUTH.Google.client_id'],
            'client_secret' => $_ENV['OAUTH.Google.client_secret'],
            'scope'         => array('userinfo_email','userinfo_profile'),
        ),
        'Google' => array(
            'client_id'     => $_ENV['OAUTH.Google.client_id'],
            'client_secret' => $_ENV['OAUTH.Google.client_secret'],
            'scope'         => array('userinfo_email','userinfo_profile'),
        ),

	)

);