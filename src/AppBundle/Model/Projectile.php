<?php
namespace AppBundle\Model;

class Projectile {
	var $damage ;
	var $target ;
	var $type ;
	function explode(){
		echo "BOUM !!\n" ;
	}

	function fail() {
		echo "Flop p'chhhhhhhh\n" ;
	}


}
