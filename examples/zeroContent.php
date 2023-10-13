<?php
include __dir__.'/../vendor/autoload.php';

$p = new \Pejman\DomParser\Parser('009');

dump( $p->document );
echo $p->document->getHtml();
