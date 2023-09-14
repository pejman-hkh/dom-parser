<?php
include __dir__.'/../vendor/autoload.php';

$p = new \Pejman\DomParser\Parser( '<div class="test">ss<span class="aa">innnerssss</span><span class="aa">innnnn</span></div><div class="test1">eee</div>');

var_dump( $p->find(".test", 0)->next );