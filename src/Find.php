<?php
namespace Pejman\DomParser;

class Find {
	function findAttr( $attrs = [], $tags ) {
		$ret = [];
		foreach( $tags as $tag ) {
			$f = true;
			foreach( $attrs as $attr => $value ) {
				if( $attr == 'class' ) {
					$classes = @$tag->attrs?$tag->attrs->classes():[];
					if( ! in_array($value, $classes ) )
						$f = false;
				} else {	
					$g = $attr == 'tag' ? @$tag->tag : @$tag->attrs->$attr;
					if( $g !== $value  )
						$f = false;
				}

			}

			if( $f )
				$ret[] = $tag;

			if( @$tag->childrens ) {
				$found = $this->findAttr( $attrs, $tag->childrens );
				foreach( $found as $a ) {
					$ret[] = $a;
				}
			}
		}

		return $ret;
	}

	function find( $query = '', $tags = [] ) {
		if( ! $query )
			return $tags;
		$q = new Query();
		$q->iq = 0;
		$q->query = $query;
		while(  $query = $q->getQueries() ) {

			$q->mQuery = $query;
			$q->miq = 0;
			$attrs = $q->parseQuery();

			$tags = $this->findAttr( $attrs, $tags);
		}
		
		return $tags;
	}
}