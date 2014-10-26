<?php

	include_once( "classes/led.php" );

	$response	=	'API call couldn\'t be made';
	
	$deviceId					=	'55ff6e065075555326371487';
	
	$url						=	'https://api.spark.io/v1/devices/' . $deviceId . '/';
	$sparkPost['access_token']	=	'29472412f89c75e42a9f59835fa2dc532cd48041';
	$function					=	'led';
	$sparkPost['params']		=	'l1,LOW';

	// Check LED Status
	$ledStatus[0]	=	apiCall( $url . 'ledStatus' , array( 'access_token' => $sparkPost['access_token'], 'params' => 'l1' ) );
	$ledStatus[1]	=	apiCall( $url . 'ledStatus' , array( 'access_token' => $sparkPost['access_token'], 'params' => 'l2' ) );


	var_dump( $_POST );

	var_dump( $url );

	if ( isset( $_POST[ 'submit' ] ) )
	{
		$deviceId						=	$_POST[ 'deviceId' ];
		$sparkPost[ 'access_token' ]	=	$_POST[ 'accessToken' ];
		$function						=	$_POST[ 'function' ];
		$url							.=	$function;
		$sparkPost['params']			=	$_POST['params'];

		var_dump( $url );

		$response	=	apiCall( $url, $sparkPost );
	}

	if ( isset( $_POST['changeLed'] ) )
	{
		if ( isset( $_POST['led1'] ) && isset( $_POST['led1'] ) !=  $ledStatus[ 0 ][ 'return_value'])
			apiCall( $url . 'led' , array( array( 'access_token' => $sparkPost['access_token'], 'params' => 'l1' )  ) ) 
	}

	function apiCall( $url, $params, $type = "POST" )
	{
		$paramsString	=	http_build_query( $params );

		var_dump( $paramsString );

		$ch = curl_init();

		curl_setopt( $ch, CURLOPT_URL, $url);
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false);

		if ( $type == "POST" ) curl_setopt( $ch, CURLOPT_POST, 1);

		curl_setopt( $ch, CURLOPT_POSTFIELDS, $paramsString);

		// receive server response ...
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$response = curl_exec ($ch);

		curl_close ($ch);

		return json_decode( $response, true );
	}

?>

<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Untitled</title>
        <link rel="stylesheet" href="css/style.css">
        <link rel="author" href="humans.txt">
    </head>
    <body>
        
		<form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST" class="ledStatus">

			<label for="led1">L1</label>
				<input type="checkbox" <?= ( $ledStatus[ 0 ][ 'return_value'] ) ? 'checked' : '' ?> name="led1">
			

			<label for="led1">L2</label>
				<input type="checkbox" <?= ( $ledStatus[ 1 ][ 'return_value'] ) ? 'checked' : '' ?> name="led2">
			

			<input type="hidden" name="changeLed">

		</form>	


		<form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST">
			
			<ul>
				<li>
					<label for="deviceId">Device Id</label>
					<input type="text" name="deviceId" value="<?= $deviceId ?>">
				</li>
				<li>
					<label for="access_token">Access token</label>
					<input type="text" name="accessToken" value="<?= $sparkPost['access_token'] ?>">
				</li>
				<li>
					<label for="function">function</label>
					<input type="text" name="function" value="<?= $function ?>">
				</li>
				<li>
					<label for="function">params</label>
					<input type="text" name="params" value="<?= $sparkPost['params'] ?>">
				</li>
			</ul>

			<input type="submit" name="submit">

		</form>

		<pre><?php var_dump( $response ) ?></pre>

        <script src="js/main.js"></script>
    </body>
</html>