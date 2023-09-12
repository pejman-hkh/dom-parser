<?php
include '../vendor/autoload.php';

$p = new \Pejman\DomParser\Parser( file_get_contents('data/a.txt') );

print_r( $p->find() );
print_r($tests = $p->find(".test"));
$test = $tests[0];
print_r( $test->next());
print_r( $test->prev()->prev() );

echo $test->text();

print_r( $p->find("body") );
print_r( $p->find("input[type=button]") );