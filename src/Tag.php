<?php
namespace Pejman\DomParser;

class Tag {

	function __construct() {
	}

	function __get( $key ) {
		if( $key == 'html' )
			return $this->getHtml();

		if( isset($this->attrs->$key) )
			return $this->attrs->$key;

		return @$this->$key;
	}

	function __set( $key, $value ) {

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
		return $this->attrs->get( $name );
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
		if( $tag->tag == 'script' ) {
			return '<script'.(@$tag->attrs?' '.$tag->attrs->makeAttrsText():'').'>'.$tag->content.'</script>';
		} else if( $tag->tag == 'comment' ) {
			return;
		} else if( $tag->tag == 'php' ) {
			return '<?'.$tag->content.'?>';
		}

		if( in_array( $tag->tag, Parser::$hasNoEndTags ) ) {
			return '<'.$tag->tag.(@$tag->attrs?' '.$tag->attrs->makeAttrsText():'').' />';
		}
		
		return '<'.$tag->tag.(@$tag->attrs?' '.$tag->attrs->makeAttrsText():'').'>'.($content).'</'.$tag->tag.'>';
	}

	private function concatHtmls( $childrens ) {
		$html = '';
		if( @$childrens ) foreach( $childrens as $child ) {
			if( @$child->tag == 'empty' || $child->tag == 'cdata' )
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

	function updateParentsHtml( $parent ) {
		$parent->html = $parent->getHtml1();
		if( isset($parent->parent) )
			$this->updateParentsHtml( $parent->parent );
	}

	function getHtml() {
		return $this->html();
	}

	function html( $html = '' ) {
		if( $html ) {
			$p = new Parser( $html );
			$childs = $p->find();
			$this->childrens = $childs;
			foreach( $childs as $child ) {
				$child->parent = $this;
			}

			$this->html = $this->getHtml1();
			$this->updateParentsHtml( $this->parent );
			return $this;
		}

		return $this->getHtml1();
	}

	function getHtml1() {
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
			if( isset($child->content) )
				$html .= $child->content;
			
			if( isset($child->childrens) ){
				if( $t = $this->concatTexts( $child->childrens ) )
					$html .= $t;
			}
		}
		return $html;
	}
}