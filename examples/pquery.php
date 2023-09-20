<?php
include __dir__.'/../vendor/autoload.php';
$p = new \Pejman\DomParser\Parser( '<div id="test"><div class="test"><span>aaa</span>bbb<span>ccc</span></div></div><div class="test1">eee</div>');
\Pejman\DomParser\PQuery::$document = $p->document;


pq("#test span")->each(function( $elm ) {
	echo pq( $elm )->html()."\n";
});

print_r( pq("#test")->next()->attr('class') );