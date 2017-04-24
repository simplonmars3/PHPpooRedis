<?php

require_once('cTower.php') ;
require_once('cStoneProjectile.php') ;

class SniperTower extends Tower {
	var $type = 'sniper' ;

	function __construct() {
		parent::__construct() ;
		$this->reload(5,'stone') ;
	}

	function shoot($projectiles_count, $projectiles_type){
		echo "Actually shooting ".$projectiles_count." projectiles (".$projectiles_type.")" ;
		if($this->reserve) $this->reserve ;
	}

	final function reload($projectiles_count, $projectiles_type) {
		$count = 0 ;
		while($count < $projectiles_count){
			$newProj = new StoneProjectile ;
			array_push($this->reserve, $newProj) ;
			$count++ ;
		}

		echo "Actually reloading ".$projectiles_count." projectiles (".$projectiles_type.")" ;
//		$this->reserve += $projectiles_count ;
	}



}
