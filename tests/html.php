<?php
include __dir__.'/../vendor/autoload.php';

$p = new \Pejman\DomParser\Parser( '<div id="first"><div class="test"><span>aaa</span>bbb<span></span></div></div><div class="test1">eee</div>');
var_dump( $p->find(".test", 0)->html );
$p->find(".test", 0)->html('ddd');
var_dump( $p->find(".test", 0)->html );
var_dump( $p->find("#first",0)->html() );