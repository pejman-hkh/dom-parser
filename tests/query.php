<?php
include __dir__.'/../vendor/autoload.php';

$p = new \Pejman\DomParser\Parser( '<div><div class="test"><span></span><span></span></div></div><div class="test1">eee</div>');
//var_dump( $p->find(".test,.test1") );
var_dump( $p->find(".test span") );