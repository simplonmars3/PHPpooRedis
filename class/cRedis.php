<?php

// require_once 'Predis/Autoloader.php' ;
// Predis\Autoloader::register() ;

class ChessRedis {
	private $Redis = null ;

	function __construct() {
		if($this->Redis) return $this->Redis ;

		try {
                    $this->Redis = new Predis\Client() ;
//                     $this->Redis = new Predis\Client(array(
//                             'scheme' => 'unix',
//                             'path' => "/var/run/redis/redis.sock"
//                     )) ;

			// echo 'Connected to Redis :)' ;

		}
		catch(Exception $e ) {
			echo 'Could not connect to Redis...' ;
			echo $e->getMessage() ;
		}
	}

	function saveHash($key, $hashToSave) {
		// echo 'Savin hash !!!'. $key ;
		return $this->Redis->hmset($key, $hashToSave) ;
	}

	function get($keyname) {
		return $this->Redis->hgetall($keyname) ;
	}

	function batchSet($keyValuesList){
		$responses = $this->Redis->pipeline() ;
		foreach($keyValuesList as $key => $value){
			$responses->set($key,$value)->get($key) ;
		}
		return $responses->execute() ;

	}

function locationIsAvailable($location) {

}

function saveNewPiece($location, $pieceObject, $gameId) {

	// Let's craft a convenient key ...

	// Build correspondence type -> object list
	$this->Redis->sadd(
		$pieceObject->getType().':'.$gameId,
		$pieceObject
		) ;

		// build correspondence boardCell -> availability
		$this->Redis->set(
			'occupiedcell:'.$gameId.':'.$location['x'].':'.$location['y'],
			1
			) ;


}

}
