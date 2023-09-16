<?php
include __dir__.'/../vendor/autoload.php';

function file_cache_content( $url ) {
	$file = __dir__.'/data/'.md5( $url );
	if( file_exists( $file ) )
		return file_get_contents( $file );
	file_put_contents( $file, $c = file_get_contents( $url ) );
	return $c;
}

$p = new \Pejman\DomParser\Parser( file_cache_content('http://feeds.bbci.co.uk/news/stories/rss.xml') );

echo $p->document->find("title",0)->html();
echo "\n";
echo $p->document->find("description",0)->html();
echo "\n";
\Pejman\DomParser\dump( $p->document->find("link", 0) );

echo "\n";
exit();
foreach( $p->find("channel item") as $item ) {
	echo $item->find("title",0)->html()."\n";
}
