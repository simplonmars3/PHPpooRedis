<?php
namespace AppBundle\Model;

use AppBundle\Model\Tower;
// require_once('cTower.php') ;

class FireTower extends Tower {
	var $action_radius = 5 ;

	function __construct() {
		parent::__construct() ;
	}

}
