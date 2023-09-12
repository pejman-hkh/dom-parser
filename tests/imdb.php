<?php
include '../vendor/autoload.php';

$p = new \Pejman\DomParser\Parser( file_get_contents('data/fight-club.txt') );

print_r( $p->find(".ipc-image")[0]->attrs->src );
