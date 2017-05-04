<?php
namespace AppBundle\Model;

use Symfony\Component\Validator\Constraints\DateTime;

use AppBundle\Connector\ChessRedis;
// require_once 'cRedis.php' ;

class GameMgt {
  private $id ;
  private $score ;
  private $boardSize ;
  private $playersList    = array() ;
  private $piecesList     = array() ;
  private $toursHistory   = array() ;
  private $dates          = array('begin'=>null, 'expire'=>null) ;
  private $redisCnx ;
  private $gameCreator;

  function __construct($originController, $boardSize = 8) {
    // echo 'Constructing...' ;
    $this->redisCnx         = new ChessRedis($originController) ;
//     $this->redisCnx         = new ChessRedis() ;
    $this->id               = uniqid() ;
    $this->dates['begin']   = new \DateTime() ;
    $this->boardSize        = $boardSize ;

  }

  public function getId() {
    return $this->id ;
  }

  public function getAll() {
    return $this->redisCnx->getAllGames() ;
  }

  public function getById($id) {
    $backupdata = $this->redisCnx->getGameById($id) ;
    $this->id = $backupdata['id'] ;
    $this->gameCreator = $backupdata['gameCreator'] ;
    $this->score = $backupdata['score'] ;
    $this->boardSize = $backupdata['boardSize'] ;
    $this->playersList = json_decode($backupdata['playersList'],true) ;
    $this->piecesList = json_decode($backupdata['piecesList'],true) ;
    $this->toursHistory = json_decode($backupdata['toursHistory'],true) ;
    // $this->dates = array('begin'=>new \Date($backupdata['date_begin']),'expire'=>new \Date($backupdata['date_expire'])) ;
    return $this ;
  }

  public function setCreator($creatorId) {
    $this->gameCreator = $creatorId ;
  }

  public function build() {
    $this->redisCnx->buildNewGame($this) ;
  }

    function toArray($stringsonly=null) {
      $return = array(
        'id'            => $this->id,
        'gameCreator'   => $this->gameCreator,
        'score'         => $this->score,
        'playersList'   => $this->playersList,
        'piecesList'    => $this->piecesList,
        'toursHistory'  => $this->toursHistory,
        'dates'         => $this->dates,
        'boardSize'     => $this->boardSize
      ) ;

      // Serialize non-scalar values, as this method would
      // be used to wrties hashes to redis
      if($stringsonly) {
        $return['playersList'] = json_encode($return['playersList']) ;
        $return['piecesList'] = json_encode($return['piecesList']) ;
        $return['toursHistory'] = json_encode($return['toursHistory']) ;
        $return['date_begin'] = ( $return['dates']['begin'] ? $return['dates']['begin']->format('%U') : null );
        $return['date_expire'] = ( $return['dates']['expire'] ? $return['dates']['expire']->format('%U') : null );
        $return['dates']=null;
      }

      return $return ;
    }

      function __toString() {
        return json_encode(array(
          'id'            => $this->id,
          'gameCreator'   => $this->gameCreator,
          'score'         => $this->score,
          'playersList'   => $this->playersList,
          'piecesList'    => $this->piecesList,
          'toursHistory'  => $this->toursHistory,
          'dates'         => $this->dates,
          'boardSize'     => $this->boardSize
        )) ;
      }

  function drawChessBoard() {

    for($rows=0; $rows<$this->boardSize; $rows++) {
      // For each line :

      // Write upper border
      for($cols=0; $cols<$this->boardSize; $cols++) {
        echo '---';
      }
      echo PHP_EOL ;
      // Write first vertical separator
      for($cols=0; $cols<$this->boardSize; $cols++) {
        echo '|  ';
        if($cols+1 == $this->boardSize) echo '|' ;
      }
      echo PHP_EOL ;
      // Write 2nd vertical separator
      for($cols=0; $cols<$this->boardSize; $cols++) {
        echo '|  ';
        if($cols+1 == $this->boardSize) echo '|' ;
      }

      echo PHP_EOL ;
    }
    // Write bottom border
    for($cols=0; $cols<$this->boardSize; $cols++) {
      echo '---';
    }
    echo PHP_EOL ;

  }


  function addPiece($location, $pieceObject) {
    // Check that location is NOT out of bounds...


    // Check that this location is a  vailable (from DB)


    // Save this piece's location (in DB)
    $this->redisCnx->saveNewPiece(
      $location,
      $pieceObject,
      $this->id
    ) ;

    // Add piece to pieces list (within current class)
    $this->piecesList[$pieceObject->getId()] = $pieceObject->getType() ;

    // Refresh game with new piecesList
    $this->build() ;

  }

}
