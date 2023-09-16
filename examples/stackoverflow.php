<?php
include __dir__.'/../vendor/autoload.php';

function file_cache_content( $url ) {
	$file = __dir__.'/data/'.md5( $url );
	if( file_exists( $file ) )
		return file_get_contents( $file );
	file_put_contents( $file, $c = file_get_contents( $url ) );
	return $c;
}


$p = new \Pejman\DomParser\Parser( file_cache_content('https://stackoverflow.com/tags') );

foreach( $p->find("#tags-browser",0)->children() as $tag ) {
	$link = $tag->find("a",0);
	echo $link->text()." : ";
	echo $link->href;
	echo "\n";
}
