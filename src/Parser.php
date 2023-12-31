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

			$t = '';
			if( $c1 == '=' ) {
			
				$nowAttr = $attr;
				$attr = '';
				$g = $this->html[ $this->i ];
				if( $g == '"' || $g == "'" ) {
					$t = $g;
					$this->i++;
				}
			
				$value = '';
				while( ! $this->empty( $c2 = $this->nextTok() ) ) {
					if( ! $t && $c2 == ' ') {
						break;
					}

					if( ! $t && $c2 == '>') {
						$this->i--;
						break;
					}

					if( $c2 == $t )
						break;
					
					$value .= $c2;
				}
		
				$attrs->$nowAttr = $value;
				$attr = '';
			}

			if( !$t && $c1 == '=')
				continue;

		
			if( $c1 == '>' ) {
				if( $attr != '' && $attr != '=' && $attr != '/' )
					$attrs->$attr = '';
				break;
			}
			$attr .= $c1;
		}

		$attrs->makeClassList();
		return $attrs;
	}

	function ParseTag() {
		if( $this->isEqual('![CDATA[') ) {
			$this->i+= 8;

			$tag = new Tag;
			$tag->tag = 'cdata';							
			return $tag;
		}

		if( @$this->html[ $this->i+1 ] == '/' ) $this->i++;

		$tag = '';
		while( ! $this->empty( $c1 = $this->nextTok() ) ) {

			if( $c1 == '>') {
				break;
			}

			if( $c1 == ' ') {
		
				$attrs = $this->ParseAttr();
				break;
			}
		
			$tag .= $c1;
		}

		$ret = new Tag;
		$ret->tag = $tag;
	
		if( isset($attrs) )
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

	function getUntil( $char ) {
		$string = substr( $this->html, $this->i, strlen( $this->html ) );
		$pos = strpos($string, $char);
		$ret = substr( $this->html, $this->i, $pos );
		$this->i += $pos;
		return $ret;
	}

	function parseContents() {


		//$content = $this->getUntil('<');

		$this->i--;
		$content = '';
		while( ! $this->empty( $c1 = $this->nextTok() )  ) {

			if( $c1 == '<' ) {
				break;
			}
			$content .= $c1;
		}

		$this->i--;

		$tag = new Tag;
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
			if( $this->isEqual('-->') ) 
				break;

			$content .= $c1;
		}
		$this->i += 3;

		$tag = new Tag;
		$tag->tag = 'comment';
		$tag->content = $content;

		return $tag;
	}

	function parsePhp() {

		$this->i += 1;
		$content = '';
		while( ! $this->empty( $c1 = $this->nextTok() ) ) {
			if( $this->isEqual('?>') ) 
				break;

			$content .= $c1;
		}
		$this->i += 2;

		$tag = new Tag;
		$tag->tag = 'php';
		$tag->content = $content;

		return $tag;		
	}

	function next1() {
		$c = @$this->html[$this->i++];
		if( $this->empty( $c ) ) return ;
		
		if( $c == '<') {
			if( $this->isEqual('!--') )
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

	function parseCData() {
		$content = '';
		while( ! $this->empty( $c1 = $this->nextTok() ) ) {
			if( $c1 == ']' ) {
				if( $this->isEqual(']>') ) {
					break;
				}
			}

			$content .= $c1;
		}
	
		$this->i+= 2;
		return $content;
	}

	protected $isXml = false;
	public static 	$hasNoEndTags = ['comment', 'php', 'empty','!DOCTYPE', 'area', 'base', 'col', 'embed', 'param', 'source', 'track', 'meta', 'link', 'br', 'input', 'hr', 'img'];

	function getTag() {

		$tag = $this->next();
		if( ! isset($tag) )
			return;

		if( $tag->tag == 'cdata' ) {
			$tag->content = $this->parseCData();
			return $tag;
		}

		if( substr($tag->tag,0,4) == '?xml' ) { $this->isXml = true; return $tag; }

		$hasNoEndTags = self::$hasNoEndTags;

		if( $this->isXml ) {
			unset( $hasNoEndTags[11] );
		}

		if( in_array( @$tag->tag, $hasNoEndTags ) ) return $tag;

		if( isset($tag->isEnd) ) return $tag;

		if( $tag->tag == 'script' ) {
			$content = $this->parseScriptInner();
			$tag->content = $content;
		} else {
			$childrens = $this->parse( $tag );
			if( $childrens )
				$tag->childrens = $childrens;			
		}

		if( isset( $tag->tag ) && @$tag->tag == @$this->current->tag ) {
			return $tag;
		}

		while( $etag = $this->next() ) {
			if( isset( $etag->tag ) && $tag->tag == @$etag->tag )
				break;
		}

		return $tag;
	}

	function parse( &$parent = '' ) {
		$tags = [];
		$stag = new Tag;
		$eq = 0;
		while( $tag = $this->getTag() ) {

			if( isset($tag->isEnd) && @$parent->tag == @$tag->tag ) break;

			if( isset( $tag->tag ) && $tag->tag == 'empty' && empty( trim($tag->content) ) )
				continue;

			if( ! isset($tag->isEnd) ) {
				$tag->eq = $eq++;
				$tag->prev = $stag;
				$tag->parent = $parent;
				$stag->next = $tag;
				$tags[] = $tag;
			}

			$stag = $tag;
		}

		return $tags;
	}


	function find( $query = '', $index = [] ) {
		$f = new Find();
		return $f->find( $query, $this->document->childrens, $index );
	}

	function __construct( $html ) {
		$this->tags = [];
		$this->html = $html;
		$this->i = 0;

		$document = new Tag;
		$document->tag = 'document';
		$document->childrens = $this->parse( $document );

		\Pejman\DomParser\PQuery::$document = $document;
		$this->document = $document;
		    
	}

}