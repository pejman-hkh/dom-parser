<?php
include __dir__.'/../vendor/autoload.php';

function ImdbApi( $content ) {
	$mtime = microtime(1);
	$p = new \Pejman\DomParser\Parser( $content );
	$epic =  $p->find(".ipc-image")[0];
	$mainPic = [ $epic->src, $epic->srcSet ];

	$selm = $p->find("div[data-testid=hero-rating-bar__aggregate-rating__score]",0);
	$sselm = $selm->find("span");
	$rate = $sselm[0]->text();
	$rated = $selm->next->next->html();
	$telm = $p->find("h1[data-testid=hero__pageTitle]",0);
	$mainTitle = $telm->text();
	foreach( $telm->next->find("li") as $a ) {
		$info[] = $a->text();
	}
	
	$plot = $p->find("p[data-testid=plot] span",0)->text();


	$arr = ['Director','Writers', 'Stars', 'Directors', 'Writer', 'Star'];
	$casts = new \StdClass;
	foreach( $p->find(".ipc-inline-list") as $ipc ) {
		$title =  $ipc->parent->prev->html;
		if( in_array( $title, $arr ) ) {
			$casts->$title = [];
			foreach( $ipc->find("a") as $a) {
				$casts->$title[] = (object)[ 'name' => $a->html, 'link' => $a->href ];
			}
		}
	}

	$topcast = [];
	foreach( $p->find("div[data-testid=title-cast-item]") as $cast ) {
		$pic = @$cast->find("img",0);
		$link = $cast->find("a",0);
		$topcast[] = (object)[ 'name' => $link->{'aria-label'}, 'link' => $link->href, 'pic' => @$pic->src, 'pics' => @$pic->srcSet ];
	}



	$eret = new \StdClass;
	$eret->title = $mainTitle;
	$eret->info = $info;
	$eret->rate = $rate;
	$eret->rated = $rated;
	$eret->pic = $mainPic;
	$eret->topcast = $topcast;
	$eret->casts = $casts;

	return $eret;
}

$c = file_get_contents(__dir__.'/data/fight-club.txt');
$ret = ImdbApi( $c );
print_r( $ret );
