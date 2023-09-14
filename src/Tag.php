<?php
namespace Pejman\DomParser;

class Tag {

	function __construct() {
	}

	function __get( $key ) {
		if( $this->attrs->$key )
			return $this->attrs->$key;
		return $this->$key;
	}

	function __set( $key, $value ) {
		if( $this->attrs->$key )
			$this->attr($key, $value);

		$this->$key = $value;
	}

	function find( $query, $index = []) {
		$f = new Find();
		return $f->find( $query, [ $this ], $index );
	}

	function remove() {
		unset( $this->parent->childrens[ $this->eq ] );
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

	function parent() {
		return $this->parent;
	}


	function next() {
		return $this->next;
	}	

	function prev() {
		return $this->prev;
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
				if( @$child->childrens )
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