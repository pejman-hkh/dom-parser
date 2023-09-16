<?php
namespace React;
include __dir__.'/../vendor/autoload.php';

function e( $html ) {
	return new \Pejman\DomParser\Parser( $html );
}

function render( $html ) {
	$el = e( $html );
	$ret = '';
	foreach( $el->document->childrens as $child ) {

		if( ctype_upper( substr($child->tag, 0, 1) ) && function_exists( __NAMESPACE__.'\\'.$child->tag ) ) {
			$fn = $child->tag;
		
			$component = call_user_func_array(__NAMESPACE__.'\\'.$fn, [ $child->attrs, render($child->html()) ] );
			$ret .= $component;
		} else {
			if( $child->tag == 'empty')
				$ret .= $child->content;
			else
				$ret.= $child->makeHtml($child, render( $child->html() ));			
		}

	}

	return $ret;
}

echo render( '<Header title="test">test <Link to="https://www.google.com/">Google</Link></Header>
<Footer title="test">test</Footer>' );


function Header( $props, $childrens ) {
	return render('<header><nav><ul><li><a href="/">Home</a></li></ul></nav>'.$childrens.'</header><h1>'.@$props->title.'</h1>');
}

function Link( $props, $childrens ) {
	return render('<a href="'.$props->to.'">'.$childrens.'</a>');
}

function Footer( $props, $childrens ) {
	return render('<footer><Link to="https://www.peji.ir">Peji</Link> '.$childrens.'</footer>');
}