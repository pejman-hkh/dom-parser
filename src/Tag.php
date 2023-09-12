<?php
namespace Pejman\DomParser;

class Tag {

	function __construct( $id ) {
		$this->id = $id;		
	}

	function find() {

	}

	function parent() {

	}

	function next() {
		$tags = Parser::$allTags;
		return $this->findNext( $tags );
	}	

	private function findNext( $tags ) {
		
		$next = false;
		$ret = [];
		foreach( $tags as $tag ) {
			if( $next )
				return $tag;
			if( $tag->id == $this->id )
				$next = true;

			if( @$tag->childrens ) {
				if( $n = $this->findNext( $tag->childrens ) )
					return $n;
			}
		}
		return;
	}

	function prev() {
		$tags = Parser::$allTags;
		return $this->findPrev( $tags );
	}

	private function findPrev( $tags ) {
		
		$ptag = '';
		foreach( $tags as $tag ) {

			if( $tag->id == $this->id )
				return $ptag;

			if( @$tag->childrens ) {
				if( $n = $this->findPrev( $tag->childrens ) )
					return $n;
			}

			$ptag = $tag;
		}

	}

	function makeHtml( $tag, $content ) {
		return '<'.$tag->tag.(@$tag->attrs?' '.$tag->attrs->makeAttrsText():'').'>'.$content.'</'.$tag->tag.'>';
	}

	function getHtml( $childrens ) {
		$html = '';
		foreach( $childrens as $child ) {
			if( @$child->tag == 'empty' )
				$html .= $child->content;
			else {
				$ct = '';
				if( $child->childrens )
					$ct = $this->getHtml( $child->childrens );
				$html .= $this->makeHtml( $child, $ct );
			}

		}
		return $html;
	}	

	function html() {
		return $this->getHtml( $this->childrens );
	}

	function outerHtml() {
		return $this->makeHtml( $this, $this->getHtml( $this->childrens ) );
	}

	function text() {
		return $this->getText( $this->childrens );
	}

	function getText( $childrens ) {
		$html = '';
		foreach( $childrens as $child ) {
			if( @$child->content )
				$html .= $child->content;
			
			if( @$child->childrens ){
				if( $t = $this->getText( $child->childrens ) )
					$html .= $t;
			}
		}
		return $html;
	}
}