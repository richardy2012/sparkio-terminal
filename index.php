<?php

	
	ini_set('max_execution_time', 5);

	session_start();


	if ( isset( $_POST['clear'] ) )
	{
		session_destroy();
		refresh( );
	}

	if ( isset( $_POST['update'] ) )
	{
		updateCredentials( $_POST['deviceId'], $_POST['accessToken'] );

		refresh();
	}
	
	function updateCredentials( $deviceId, $accessToken )
	{
		$ttl	=	time() + (60 * 60 * 24 * 30);

		setcookie( 'deviceId', $deviceId, $ttl );
		setcookie( 'accessToken', $accessToken, $ttl );
	}

	function refresh( $customPage = false )
	{
		$url 	=	( $customPage ) ? $customPage : $_SERVER['REQUEST_URI'];

		header( "location: " . $url );
	}

	$configFile		=	'config.php';
	$url			=	explode( ".", $_SERVER['HTTP_HOST'] );
	$serverDomain	=	array_pop( $url );
	$local			=	false;

	if ( $serverDomain == "local" )
	{
		$configFile	=	'_localConfig.php';
		$local		=	true;
	}

	include_once( 'classes/' . $configFile );

	spl_autoload_register( function( $class ) {
		
		include_once( 'classes/' . $class . '.php' );
	
	} );

	$accessToken	=	( isset( $_COOKIE[ 'accessToken' ] ) ) ? $_COOKIE[ 'accessToken' ] : ACCESS_TOKEN;
	$deviceId		=	( isset( $_COOKIE[ 'deviceId' ] ) ) ? $_COOKIE[ 'deviceId' ] : DEVICE_ID;

	$input	=	'';

	$result	=	false;
	
	$spark = new API( $deviceId, $accessToken );

	$terminal = new Terminal( );

	$status = $spark->getDeviceProperties();

	if ( isset( $_POST[ 'submit' ]) )
	{
		// Check if new access-token or device is being request, if so, load new instance
		if ( $_POST[ 'accessToken' ] != $accessToken || $_POST[ 'deviceId' ] != $deviceId )
		{

			$accessToken	=	$_POST[ 'accessToken' ];
			$deviceId		=	$_POST[ 'deviceId' ];

			updateCredentials( $deviceId, $accessToken );

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
		else
		{
			$function 	=	'';
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

		$type	=	'POST';

		// If the params are set to false, a variable is requested and the GET variable should be passed to API call
		if ( $params === FALSE )
		{
			$type	=	'GET';
		}

		$result 	=	$spark->call( $function, $params, $type );

		$terminal->addResult( $result );

		$status = $spark->getDeviceProperties();

		refresh();
	}

	$history = $terminal->getHistory();

	$inputHistory = json_encode( $terminal->getInputHistory() );

	include_once( 'views/terminal.html' );

?>