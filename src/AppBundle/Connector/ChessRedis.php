<?php
namespace AppBundle\Connector;

// require_once '../../../../vendor/predis/predis/src/Autoloader.php' ;
// Predis\Autoloader::register() ;

class ChessRedis {
	private $Redis = null ;

	function __construct($originController) {
		if($originController) {
        $this->Redis = $originController ;
        return $this->Redis ;
    }
    else throw(new Exception('Could not get connection to Redis service')) ;
	}


	function buildNewGame($game) {
		if(gettype($game) == "object") $game = $game->toArray(true) ;

    $this->Redis->hmset('currentgame:'.$game['id'],$game) ;
    $this->Redis->sadd('currentgames',$game['id']) ;
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

function getAllGames() {
	return $this->Redis->smembers('currentgames') ;
}

function getGameById($id) {
	if($this->Redis->sismember('currentgames', $id)) {
		$gamedata = $this->Redis->hgetall('currentgame:' . $id) ;
		return $gamedata ;
	}

	return null ;
}

function saveNewPiece($location, $pieceObject, $gameId) {

	// Let's craft a convenient key ...

	// Build correspondence type -> object list
	$this->Redis->geoadd(
		'geochessboard:'.$gameId,
		$location['lon'], $location['lat'],
		$pieceObject->getId()
		) ;

	$this->Redis->sadd('piecesofgame:'.$gameId, $pieceObject->getId()) ;

		// build correspondence boardCell -> availability
		// $this->Redis->set(
		// 	'occupiedcell:'.$gameId.':'.$location['x'].':'.$location['y'],
		// 	1
		// 	) ;


}

}
