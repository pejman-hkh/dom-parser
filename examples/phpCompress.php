<?php
include __dir__.'/../vendor/autoload.php';

$p = new \Pejman\DomParser\Parser( file_get_contents(__dir__.'/data/test.php') );

//dump( $p->document );

echo $p->document->getHtml();

