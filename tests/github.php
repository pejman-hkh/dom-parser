<?php
include __dir__.'/../vendor/autoload.php';

$p = new \Pejman\DomParser\Parser( file_get_contents('https://github.com/pejman-hkh?tab=repositories') );

var_dump( $p->find("#user-repositories-list",0)->find("a",0)->attrs->href );

echo $p->find("#user-repositories-list",0)->html();

$p1 = new \Pejman\DomParser\Parser( file_get_contents(__dir__.'/data/github-bug-in-html.html') );

var_dump( $p1 );