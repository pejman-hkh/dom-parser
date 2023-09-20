<?php
include __dir__.'/../vendor/autoload.php';
$p = e( '<div id="test"><div class="test"><span>aaa</span>bbb<span>ccc</span></div></div><div class="test1">eee</div>');

pq("#test span")->each(function( $elm ) {
	echo pq( $elm )->html()."\n";
});

print_r( pq("#test")->next()->attr('class') );
echo "\n";
pq("#test")->addClass("added-class");
echo pq("#test")->outerHtml();
echo "\n";
pq("#test")->removeClass("added-class");
echo pq("#test")->outerHtml();
echo "\n";
pq("#test")->addClass("added-class");
echo pq("#test")->outerHtml();
echo "\n";
