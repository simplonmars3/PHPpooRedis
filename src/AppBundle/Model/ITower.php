<?php
namespace AppBundle\Model;

interface ITower {
        function getId() ;
        function shoot($projectiles_count, $projectiles_type) ;
        function reload($projectiles_count, $projectiles_type) ;
}
