<?php
include __dir__.'/../vendor/autoload.php';
$p = new \Pejman\DomParser\Parser( file_get_contents(__dir__.'/data/test.xml') );

var_dump( $p->find("last_updated",0)->html() );