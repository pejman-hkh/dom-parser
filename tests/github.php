<?php
include __dir__.'/../vendor/autoload.php';

function file_cache_content( $url ) {
	$file = __dir__.'/data/'.md5( $url );
	if( file_exists( $file ) )
		return file_get_contents( $file );
	file_put_contents( $file, $c = file_get_contents( $url ) );
	return $c;
}

$p = new \Pejman\DomParser\Parser( file_cache_content('https://github.com/pejman-hkh?tab=repositories') );

echo "My Repositories : ";
foreach( $p->find("#user-repositories-list li") as $repos ) {
	echo $repos->find("a",0)->href;
	echo "\n";
}

exit();

echo $p->find("#user-repositories-list",0)->html();

$p1 = new \Pejman\DomParser\Parser( file_get_contents(__dir__.'/data/github-bug-in-html.html') );

var_dump( $p1 );