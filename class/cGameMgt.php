<?php

class cGameMgt {
  private $id ;
  private $score ;
  private $playersList    = [] ;
  private $piecesList     = [] ;
  private $toursHistory   = [] ;
  private $dates          = array('begin'=>null, 'expire'=>null) ;

  function __construct() {
    echo 'Constructing...' ;
    $this->dates['begin'] = new DateTime() ;
  }

  function __toString() {
    return array(
      'id'            => $this->id,
      'score'         => $this->score,
      'playersList'   => $this->playersList,
      'piecesList'    => $this->piecesList,
      'toursHistory'  => $this->toursHistory,
      'dates'          => $this->dates
    ) ;
  }

}
