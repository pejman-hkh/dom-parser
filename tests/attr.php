<?php
include __dir__.'/../vendor/autoload.php';

$p = new \Pejman\DomParser\Parser( '<span class="text-normal" data-menu-button-text>Stars</span><header data-light-theme=light data-dark-theme=dark></header><div id=test></div><div class="test">ss<span class="aa">innnerssss</span><span class="aa">innnnn</span></div><div class="test1">eee</div>');

print_r( $p );

print_r( $p->find(".aa", 0)->attr('aaa','bbb') );