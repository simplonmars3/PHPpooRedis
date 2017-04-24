<?php

require_once 'Predis/Autoloader.php' ;
Predis\Autoloader::register() ;

class ChessRedis {
	private $Redis = null ;

	function __construct() {
		if($this->Redis) return $this->Redis ;

		try {
			$this->Redis = new Predis\Client() ;

			echo 'Connected to Redis :)' ;

		}
		catch(Exception $e ) {
			echo 'Could not connect to Redis...' ;
			echo $e->getMessage() ;
		}
	}

	function saveHash($key, $hashToSave) {
		return $this->Redis->hmset($key, $hashToSave) ;
	}

	function batchSet($keyValuesList){
		$responses = $this->Redis->pipeline() ;
		foreach($keyValuesList as $key => $value){
			$responses->set($key,$value)->get($key) ;
		}
		return $responses->execute() ;

	}


}
