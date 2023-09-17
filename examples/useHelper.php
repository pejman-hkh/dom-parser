<?php
include __dir__.'/../vendor/autoload.php';

$p = e( '<div><div class="test">ss<span class="aa">innnerssss</span><span class="aa">innnnn</span></div></div><div class="test1">eee</div>');

dump( $p->document );