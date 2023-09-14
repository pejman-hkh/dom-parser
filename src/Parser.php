<?php

namespace Pejman\DomParser;

class Parser {

	function ParseAttr() {
		$attrs = new Attrs;
		$attr = '';
		$nowAttr = '';

		while( ! $this->empty( $c1 = $this->nextTok() ) ) {

			if( $c1 == ' ' ) {
				$attr = '';
				continue;
			}

			if( $c1 == '=' ) {
				$nowAttr = $attr;
				$attr = '';

				$t = '';
				if( $this->html[ $this->i ] == '"' || $this->html[ $this->i ] == "'" ) {
					$t = $this->html[ $this->i ];
					$this->i++;
				}
			
				$value = '';
				while( ! $this->empty( $c2 = $this->nextTok() ) ) {
					
					if( $c2 == $t ) {
						break;
					}

					$value .= $c2;
				}

				$attrs->$nowAttr = $value;

				$attr = '';
			}

			$attr .= $c1;
		
			if( $c1 == '>' ) {
				break;
			}
		}

		return $attrs;
	}

	function ParseTag() {
		if( $this->html[ $this->i+1 ] == '/' ) $this->i++;

		$tag = '';

		while( $c1 = @$this->html[ $this->i++ ] ) {

			if( $c1 == '>') {
				break;
			}

			if( $c1 == ' ') {
		
				$attrs = $this->ParseAttr();
				break;
			}
		
			$tag .= $c1;
		}

		$ret = new Tag($this);
		$ret->tag = $tag;
	
		if( @$attrs )
			$ret->attrs = @$attrs; 

		if( substr($tag,0,1) == '/' ) {
			$ret->isEnd = true;
			$ret->tag = substr($tag,1);
		}

		if( substr( $tag, -1) == '/' ) {
			$ret->tag = substr( $tag, 0, -1);
		}
		
		return $ret;
	}

	function parseContents() {
		$this->i--;
		$content = '';
		while( ! $this->empty( $c1 = $this->nextTok() )  ) {
			if( $c1 == '<' ) {
				break;
			}
			$content .= $c1;
		}
		$this->i--;

		$tag = new Tag($this);
		$tag->tag = 'empty';
		$tag->content = $content;

		return $tag;
	}

	function nextTok() {
		$ret = @$this->html[ $this->i++ ];
		return $ret;
	}

	function empty( $a ) {
		return empty($a) && $a !== '0'; 
	}

	function parseComment() {
		$this->i += 3;
		$content = '';
		while( ! $this->empty( $c1 = $this->nextTok() ) ) {

			if( $c1 == '-' && $this->html[ $this->i] == '-' &&  $this->html[ $this->i+1] == '>' ) 
				break;

			$content .= $c1;
		}

		$this->i += 3;

		$tag = new Tag($this);
		$tag->tag = 'comment';
		$tag->content = $content;

		return $tag;
	}

	function next1() {

	
		$c = @$this->html[$this->i++];

		if( ! $c ) return ;
	
		if( $c == '<') {
		
			if( $this->html[ $this->i ] == '!' && $this->html[ $this->i+1 ] == '-' && $this->html[ $this->i+2 ] == '-' )
				return $this->parseComment();

			if(  $this->html[ $this->i ] == ' ' ) {
				$this->i++;
				$cn = $this->parseContents();
				$cn->content = '<'.$cn->content;
				return $cn;
			}

			return $this->ParseTag();
		} else {
			return $this->parseContents();
		}
	
	}

	function next() {
		$this->current = $this->next1();
		return $this->current;
	}

	function isEqual( $text ) {
		$i = $this->i;
		$html = $this->html;
		$j = 0;
		while( $c = @$text[$j++]) {
			if( $html[$i++] != $c )
				return false;
		
		}

		return true;
	}

	function parseScriptInner() {
		$content = '';
		while( ! $this->empty( $c1 = $this->nextTok() ) ) {
			if( $c1 == '<' ) {
				if( $this->isEqual('/script') ) {
					break;
				}
			}

			$content .= $c1;
		}
	
		$this->i+= 8;
		return $content;
	}

	function getTag() {

		$tag = $this->next();
		if( ! @$tag )
			return;

		if( in_array( @$tag->tag, ['comment', 'empty','!DOCTYPE', 'area', 'base', 'col', 'embed', 'param', 'source', 'track', 'meta', 'link', 'br', 'input', 'hr', 'img'] ) ) return $tag;

		if( @$tag->isEnd ) return $tag;

		if( $tag->tag == 'script' ) {
			$content = $this->parseScriptInner();
			$tag->content = $content;
		} else {
			$childrens = $this->parse( $tag );
			if( $childrens )
				$tag->childrens = $childrens;			
		}


		if( @$tag->tag == @$this->current->tag ) {
			return $tag;
		}

		while( $etag = $this->next() ) {
			if( $tag->tag == @$etag->tag )
				break;
		}

		return $tag;
	}

	function parse() {

		$tags = [];

		while( $tag = $this->getTag() ) {

			if( @$tag->isEnd ) break;

			if( @$tag->tag == 'empty' && empty( trim($tag->content) ) )
				continue;
			
			$tags[] = $tag;
		}

		return $tags;
	}

	function find( $query = '', $index = [] ) {
		$f = new Find();
		return $f->find( $query, $this->tags, $index );
	}

	public static $allTags;

	function __construct( $html ) {
		$this->tags = [];
		$this->html = $html;
		$this->i = 0;
		$this->id = 0;
		$this->tags = $this->parse();
		self::$allTags = $this->tags;
	}
}
