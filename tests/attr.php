<?php
include __dir__.'/../vendor/autoload.php';

$p = new \Pejman\DomParser\Parser( '<span class="text-normal" data-menu-button-text>Stars</span><header data-light-theme=light data-dark-theme=dark></header><div id=test></div><div class="test">ss<span class="aa" test-attr="123">innnerssss</span><span class="aa">innnnn</span></div><div class="test1">eee</div>');

var_dump( $p );
$element = $p->find(".aa", 0);
var_dump( $element->attr('aaa','bbb') );
var_dump( $element->class );

$element->{'test-attr'} = 321;

var_dump( $element->attrs );