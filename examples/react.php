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

		if( function_exists( __NAMESPACE__.'\\'.$child->tag ) ) {
			$fn = $child->tag;
		
			$component = call_user_func_array(__NAMESPACE__.'\\'.$fn, [ $child->attrs, render($child->html()) ] );
			$ret .= $component->document->getHtml();
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
	return e('<header><nav><ul><li><a href="/">Home</a></li></ul></nav>'.$childrens.'</header><h1>'.$props->title.'</h1>');
}

function Link( $props, $childrens ) {
	return e('<a href="'.$props->to.'">'.$childrens.'</a>');
}

function Footer( $props, $childrens ) {
	return e('<footer>'.$childrens.'</footer>');
}
