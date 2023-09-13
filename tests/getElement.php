<?php
include __dir__.'/../vendor/autoload.php';

$p = new \Pejman\DomParser\Parser( '<div class="test">ss<span class="aa" id="gg">innnerssss</span><span class="aa">innnnn</span></div><div class="test1">eee</div>');

print_r( $p->find(".test", 0)->getElementById('gg') );
print_r( $p->find(".test", 0)->getElementsByTagName('span') );