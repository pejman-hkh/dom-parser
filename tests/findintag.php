<?php
include __dir__.'/../vendor/autoload.php';

$p = new \Pejman\DomParser\Parser( '<div><div class="test">ss<span class="aa">innnerssss</span><span class="aa">innnnn</span></div></div><div class="test1">eee</div>');
print_r( $p->find(".test", 0)->find(".aa") );