<?php

	class Terminal
	{

		protected $inputHistory;
		protected $history;

		protected $currentKey;

		public function __construct()
		{
			$this->currentKey = false;

			if ( !isset( $_SESSION[ 'history' ] ) )
			{
				$_SESSION[ 'history' ]	=	array();
			}

			$this->history	=	&$_SESSION['history'];
		}

		public function addInput( $input )
		{
			if( $this->currentKey !== false )
			{
				$history 	=	&$this->history[ $this->currentKey ];
			}
			else
			{
				$history	=	&$this->history[] ;

				end( $this->history );         // move the internal pointer to the end of the array
				$this->currentKey = key( $this->history );  // fetches the key of the element pointed to by the internal pointer
			}

			$history['input']['time']	=	microtime( true );	
			$history['input']['text']	=	$input;
		}

		public function addResult( $result )
		{
			if( $this->currentKey !== false )
			{
				$history 	=	&$this->history[ $this->currentKey ];
			}
			else
			{
				$history	=	&$this->history[] ;

				end( $this->history );         // move the internal pointer to the end of the array
				$this->currentKey = key( $this->history );  // fetches the key of the element pointed to by the internal pointer
			}

			$history['result']['time']			=	microtime( true);	
			$history['result']['responseTime']	=	$history['result']['time'] - $history['input']['time'];	
			$history['result']['text']			=	$result;
		}

		public function getHistory()
		{
			return $this->history;
		}

		public function getInputHistory()
		{
			$inputs	=	array();

			foreach ($this->history as $value) {
				$inputs[]	=	$value['input']['text'];
			}

			return $inputs;
		}

	}

?>