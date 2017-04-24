<?php

class cGameMgt {
  private $id ;
  private $score ;
  private $boardSize ;
  private $playersList    = [] ;
  private $piecesList     = [] ;
  private $toursHistory   = [] ;
  private $dates          = array('begin'=>null, 'expire'=>null) ;

  function __construct($boardSize = 8) {
    echo 'Constructing...' ;
    $this->dates['begin']   = new DateTime() ;
    $this->boardSize        = $boardSize ;
  }

  function __toString() {
    return array(
      'id'            => $this->id,
      'score'         => $this->score,
      'playersList'   => $this->playersList,
      'piecesList'    => $this->piecesList,
      'toursHistory'  => $this->toursHistory,
      'dates'         => $this->dates,
      'boardSize'     => $this->boardSize
    ) ;
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

}
