<?php
function removeFromObject( &$object ) {
	$ret = [];
	if( is_array( $object ) ) {
		foreach( $object as $o ) {
			removeFromObject( $o );
		}
	} else {
		if( @$object->childrens )
			removeFromObject( $object->childrens );
		$object->next = 'Next';
		$object->prev = 'Prev';
		$object->parent = 'Parent';
	}
	return $object;
}

if( ! function_exists('pq') ) {
	function pq( $selector ) {
	    return new \Pejman\DomParser\PQuery( $selector );
	}
}

if( ! function_exists('dump') ) {
	function dump( $object ) {
		$t = unserialize(serialize($object));
		removeFromObject( $t );
		var_dump( $t );
	}
}

if( ! function_exists('e') ) {
	function e( $html ) {
		return new \Pejman\DomParser\Parser( $html );
	}
}