<?php

	include_once( 'classes/config.php' );

	spl_autoload_register( function( $class ) {
		
		include_once( 'classes/' . $class . '.php' );
	
	} );

	$api = new API();

	//$result = $api->call( "led", array( "L2", "LOW" ) );

	$ledRed		=	new LED( "D0" );
	$ledGreen	=	new LED( "D1" );

	if( isset( $_POST[ 'toggle' ]) )
	{
		unset( $_POST[ 'toggle' ] );

		$ledRed->checkAction( $_POST );

		$ledGreen->checkAction( $_POST );
	}


	include_once( 'views/led.html' );

?>