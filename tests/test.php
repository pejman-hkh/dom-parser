<?php
include __dir__.'/../vendor/autoload.php';

$p = new \Pejman\DomParser\Parser( file_get_contents(__dir__.'/data/a.txt') );

$tests = $p->find(".test1");
$test = $tests[0];
echo $test->outerHtml();
echo "\n";
echo( $test->next()->html() );
echo "\n";
echo( $test->prev()->html() );
echo "\n";

echo $test->text();
echo "\n";

echo( $p->find("body",0)->html() );
echo "\n";
echo( $p->find("input[type=button]",0)->html() );