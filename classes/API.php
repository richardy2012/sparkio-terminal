<?php

	class API
	{
		protected $baseUrl;
		protected $url;
		protected $accessToken;
		protected $deviceId;

		protected $deviceProperties;

		protected $lastResponse;


		public function __construct( 	$deviceId 		= 	DEVICE_ID,
										$accessToken 	= 	ACCESS_TOKEN,
										$url 			= 	URL
									)
		{
			
			$this->baseUrl 		= 	rtrim( $url, '/' ) . '/';
			$this->accessToken 	= 	$accessToken;
			$this->deviceId 	= 	$deviceId;

			$this->url			=	$this->baseUrl . $this->deviceId . '/';

			$this->deviceProperties	= $this->call( false, false, 'GET' );
		}

		public function call( $function = false, $parameters = false, $type = 'POST' )
		{
			
			$params['access_token']		=	$this->accessToken;
			
			// Check if arguments are defined
			if ( $parameters !== false )
			{
				$params['params']			=	( is_array( $parameters ) ) ? implode( ',', $parameters ) : $parameters;
			}

			$paramsString	=	http_build_query( $params );
			
			if ( $type == 'POST' )
			{
				$completeUrl	=	$this->url . ( ( $function ) ? $function : '' ); // Check if function is defined
			}
			else // 'GET'
			{
				$completeUrl	=	$this->url . $function . '?' . $paramsString;
			}

			$ch = curl_init();

			curl_setopt( $ch, CURLOPT_URL, $completeUrl);
			curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false);

			if ( $type == 'POST' ) 
			{
				curl_setopt( $ch, CURLOPT_POST, 1);
				curl_setopt( $ch, CURLOPT_POSTFIELDS, $paramsString);
			}

			// receive server response ...
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

			$response = curl_exec ($ch);

			curl_close ($ch);

			$this->lastResponse	=	$response;

			return json_decode( $response, true );
		}

		public function getDeviceProperties()
		{
			return $this->deviceProperties;
		}

	}

?>