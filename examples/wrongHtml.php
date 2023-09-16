<?php
include __dir__.'/../vendor/autoload.php';

$p = new \Pejman\DomParser\Parser( '<b><i>Some text</b></i>' );


var_dump( $p->document->find("b i",0)->html() );

