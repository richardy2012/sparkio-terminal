<?php
ini_set('max_execution_time', 60);
	include_once( 'classes/config.php' );

	spl_autoload_register( function( $class ) {
		
		include_once( 'classes/' . $class . '.php' );
	
	} );

	$accessToken	=	ACCESS_TOKEN;
	$deviceId		=	DEVICE_ID;

	$function 	=	'';
	$params		=	'';

	$result	=	false;
	
	$spark = new API( );

	$status = $spark->getDeviceProperties();

	if ( isset( $_POST['submit'] ) )
	{
		// Check if new access-token or device is being request, if so, load new instance
		if ( $_POST[ 'accessToken' ] != ACCESS_TOKEN || $_POST[ 'deviceId' ] != DEVICE_ID )
		{
			$accessToken	=	$_POST[ 'accessToken' ];
			$deviceId		=	$_POST[ 'deviceId' ];

			$spark 	=	new API( $deviceId, $accessToken );
		}
		

		$function		=	( $_POST[ 'function' ] != '' ) ? $_POST[ 'function' ] : false;;
		$params			=	( $_POST[ 'params' ] != '' ) ? $_POST[ 'params' ] : false;

		$result 	=	$spark->call( $function, $params );

		$status = $spark->getDeviceProperties();
	}

	include_once( 'views/test-center.html' );

?>