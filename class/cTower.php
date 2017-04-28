<?php
require_once('iTower.php') ;

class Tower implements ITower {
	var $id ;
	var $position;
	var $tower_type;
	var $reserve = [] ;
	private $_prop_unreachable ;

	// _
	//|_)._o   _._|_ _
	//|  | |\/(_| |_(/_

	private function _die(){
		echo 'Aaaaaaaaaaargh' ;
	}



	//|\/| _. _ o _
	//|  |(_|(_||(_
	//        _|

	function __construct($position=null,$type=null) {
		$this->position 	= $position ;
		$this->tower_type 	= $type ;
		echo 'je suis en train de me faire construire' ;
	}


	function __destruct() {
		echo 'je suis en train de me faire détruire !! Aaaaaargh !!' ;
	}

	function __toString() {
		echo 'converting to string...' ;
		return print_r($this, true) ;
	}

	function __call($fnct_name, $args) {
		echo "Unauthorised access to method ".$fnct_name." with args :" ;
		print_r($args) ;
	}

	function __get($name) {
		echo 'Unauthorized access to unreachable property '.$name ;
	}

	function __set($name, $value) {
		echo 'Trying to set unreachable prop '.$name.' to '.print_r($value,true) ;
	}

	// _
	//|_) |_ |o _
	//||_||_)||(_

	public function shoot($projectiles_count, $projectiles_type) {
		echo "Cannot shoot : wrong tower type" ;
	}

	public function reload($proj_count,$b) {
		echo "Cannot reload : wrong tower type" ;
	}

	public function getType() {
		return __CLASS__ ;
	}


}
