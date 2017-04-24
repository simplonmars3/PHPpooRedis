<?php

function incrementationpersonellementimplementee(&$valeur) {
	echo 'got value '.$valeur."\n" ;
	return ++$valeur ;
}

$valex = 2 ;

echo $valex."\n" ;
echo incrementationpersonellementimplementee($valex)."\n" ;

echo $valex."\n" ;
