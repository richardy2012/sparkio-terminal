<?php

	session_start();

	if ( isset( $_GET['session'] ) && $_GET['session'] == "destroy" )
	{
		session_destroy();
		$uri_parts = explode('?', $_SERVER['REQUEST_URI'], 2);

		header( 'location: http://' . $_SERVER['HTTP_HOST'] . $uri_parts[0] );
	}

	// ini_set('max_execution_time', 30);
	
	include_once( 'classes/config.php' );

	spl_autoload_register( function( $class ) {
		
		include_once( 'classes/' . $class . '.php' );
	
	} );

	$accessToken	=	ACCESS_TOKEN;
	$deviceId		=	DEVICE_ID;

	$input	=	'';

	$result	=	false;
	
	$spark = new API( );

	$terminal = new Terminal( );

	$status = $spark->getDeviceProperties();

	if ( isset( $_POST[ 'submit' ]) )
	{
		// Check if new access-token or device is being request, if so, load new instance
		if ( $_POST[ 'accessToken' ] != ACCESS_TOKEN || $_POST[ 'deviceId' ] != DEVICE_ID )
		{
			$accessToken	=	$_POST[ 'accessToken' ];
			$deviceId		=	$_POST[ 'deviceId' ];

			$spark 	=	new API( $deviceId, $accessToken );
		}

		// Process input
		$input	=	$_POST[ 'input' ];

		$terminal->addInput( $input );

		// Function name
		preg_match("/([^,]+\(.+?\))|([^,]+)/", $input, $function);

		if ( !empty( $function ) )
		{
			
			if ( $function[1] == '' )
			{
				$function = $function[2];
			}
			else
			{
				$function	=	$function[1];
			}
		}

		// Get function name
		$function = preg_replace( '/\(.*\).*/', "", $function );

		// Strip spaces from function name 
		$function = trim( $function );

		// Arguments
		$params = false;

		preg_match("/\b[^()]+\((.*)\)/", $input, $arguments);

		if ( !empty( $arguments ) )
		{
			// Strip spaces before and/or after argument separator (,)
			$arguments = preg_replace( '/\s*,\s*/', ',', $arguments[1] );

			//Strip spaces before and after argument list
			$arguments = trim( $arguments);

			$params	=	$arguments;
		}

		$result 	=	$spark->call( $function, $params );

		$terminal->addResult( $result );

		$status = $spark->getDeviceProperties();

		header( "location: " . $_SERVER['REQUEST_URI'] );
	}

	$history = $terminal->getHistory();

	$inputHistory = json_encode( $terminal->getInputHistory() );

	include_once( 'views/terminal.html' );

?>