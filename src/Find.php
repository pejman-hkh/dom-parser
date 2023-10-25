<?php
namespace Pejman\DomParser;

class Find {
	function findAttr( $attrs = [], $tags ) {
		$ret = [];
		foreach( $tags as $tag ) {
			$f = true;
			foreach( $attrs as $attr => $value ) {
				if( $attr == 'class' ) {
					$classes = isset($tag->attrs)?$tag->attrs->classes():[];
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

			if( isset($tag->childrens) ) {
				$found = $this->findAttr( $attrs, $tag->childrens );
				foreach( $found as $a ) {
					$ret[] = $a;
				}
			}
		}

		return $ret;
	}

	function find( $query = '', $tags = [], $index = [] ) {
		if( ! $query )
			return $tags;
		
		$q = new Query();
		$q->iq = 0;
		$q->query = $query;
		$ret = $tags;
		while(  $query = $q->getQueries() ) {

			if( $query == ',' ) {

				$q->mQuery = $q->getQueries();
				$q->miq = 0;
				$attrs = $q->parseQuery();
			
				$finded = $this->findAttr( $attrs, $tags);
				foreach( $finded as $f ) {
					$ret[] = $f;
				}
			}
			else if( ! empty( trim( $query ) ) ){
	
				$q->mQuery = $query;
				$q->miq = 0;
				$attrs = $q->parseQuery();
				$ret = $this->findAttr( $attrs, $ret);
				
			}
		}

		if( ! is_array( $index ) )
			$ret = $ret[ $index ];
		

		return $ret;
	}
}