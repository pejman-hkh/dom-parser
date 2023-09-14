<?php
namespace Pejman\DomParser;

class Tag {

	private $parser;
	function __construct( &$parser ) {
		$this->id = $parser->id++;	
		$this->parser = $parser;	
	}

	{
		return json_decode(json_encode($this), true);
	}

	function find( $query, $index = []) {
		$f = new Find();
		return $f->find( $query, [ $this ], $index );
	}

	private function notEmpty() {
		$ret = [];
		foreach($this->childrens as $child ) {
			if( $child->tag != 'empty')
				$ret[] = $child;
		}
		return $ret;
	}
	
	function getElementById( $id ) {
		return $this->find("#".$id)[0];
	}
	
	function getElementByTagName( $name ) {
		return $this->find($name)[0];
	}
	
	function getElementsByTagName( $name ) {
		return $this->find($name);
	}

	function children( $index = [] ) {
		$ret = $this->notEmpty();
		if( ! is_array( $index ) ) {
			$ret = @$ret[ $index ];
		}

		return $ret;
	}

	function attr( $name, $value = '' ) {
		if( $value )
			return $this->setAttribute( $name, $value );

		return $this->getAttribute( $name );
	}

	function setAttribute( $name, $value ) {
		$this->attrs->set( $name, $value );
		return $this;
	}
	
	function getAttribute( $name ) {
		$this->attrs->get( $name );
		return $this;
	}

	private function findParent( $ptag = [], $tags ) {

		$ret = [];
		foreach( $tags as $tag ) {
			if( $tag->id == $this->id )
				return $ptag;

			if( @$tag->childrens ) {
				if( $n = $this->findParent( $tag, $tag->childrens ) )
					return $n;
			}
		}
		return;	
	}

	function parent() {
		return $this->findParent( new Tag($this->parser), $this->parser->tags );
	}


	function next() {
		return $this->findNext( $this->parser->tags );
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
		return $this->findPrev( $this->parser->tags );
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

	private function concatHtmls( $childrens ) {
		$html = '';
		foreach( $childrens as $child ) {
			if( @$child->tag == 'empty' )
				$html .= $child->content;
			else {
				$ct = '';
				if( $child->childrens )
					$ct = $this->concatHtmls( $child->childrens );
				$html .= $this->makeHtml( $child, $ct );
			}

		}
		return $html;
	}	

	function html() {
		return $this->concatHtmls( $this->childrens );
	}

	function outerHtml() {
		return $this->makeHtml( $this, $this->concatHtmls( $this->childrens ) );
	}

	function text() {
		return $this->concatTexts( $this->childrens );
	}

	private function concatTexts( $childrens ) {
		$html = '';
		foreach( $childrens as $child ) {
			if( @$child->content )
				$html .= $child->content;
			
			if( @$child->childrens ){
				if( $t = $this->concatTexts( $child->childrens ) )
					$html .= $t;
			}
		}
		return $html;
	}
}