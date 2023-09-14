<?php
include __dir__.'/../vendor/autoload.php';

$p = new \Pejman\DomParser\Parser( file_get_contents(__dir__.'/data/a.txt') );

var_dump( $p->find() );
var_dump($tests = $p->find(".test1"));
$test = $tests[0];
echo $test->outerHtml();

var_dump( $test->next());
var_dump( $test->prev() );

echo $test->text();

var_dump( $p->find("body") );
var_dump( $p->find("input[type=button]") );