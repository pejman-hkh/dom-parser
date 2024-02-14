<?php
namespace Pejman\DomParser;

class Parser {

	private $html;
	private $i;
	private $current;
	public $document;
	protected $isXml = false;
	public static 	$hasNoEndTags = ['comment', 'php', 'empty','!DOCTYPE', 'area', 'base', 'col', 'embed', 'param', 'source', 'track', 'meta', 'link', 'br', 'input', 'hr', 'img'];

	function ParseAttr() {
		$attrs = new Attrs;
		$attr = '';
		$nowAttr = '';

		while( true ) {
			$c1 = $this->html[ $this->i++ ];
			if( empty($c1 ) && $c1 != '0' ) {
				break;
			}

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
				while( true ) {

					$c2 = $this->html[ $this->i++ ];
					if( empty($c2 ) && $c2 != '0' ) {
						break;
					}

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

	function ParseTag( &$tag ) {
		if( $this->html[ $this->i ] == '!' && $this->isEqual('![CDATA[') ) {
			$this->i+= 8;

			//$tag = new Tag;
			$tag->tag = 'cdata';
			return;							
			//return $tag;
		}

		if( @$this->html[ $this->i+1 ] == '/' ) $this->i++;

		$name = '';
		$attrs = [];
		while( true ) {

			$c1 = $this->html[ $this->i++ ];
			if( empty($c1 ) && $c1 != '0' ) {
				break;
			}

			if( $c1 == '>') {
				break;
			}

			if( $c1 == ' ') {
		
				$attrs = $this->ParseAttr();
				break;
			}
		
			$name .= $c1;
		}

		//$ret = new Tag;
		$tag->tag = $name;
	
		$tag->attrs = @$attrs; 
		$tag->isEnd = false;
		if( $name[0] == '/' ) {
			$tag->isEnd = true;
			$tag->tag = substr($name,1);
		}

		if( $name[strlen($name)-1] == '/' ) {
			$tag->tag = substr( $name, 0, -1);
		}
		
		//return $ret;
	}


	function parseContents( &$tag ) {
		$this->i--;
		$content = '';
		while( true ) {
			$c1 = $this->html[ $this->i++ ];

			if( empty($c1 ) && $c1 != '0' ) {
				break;
			}

			if( $c1 == '<' ) {
				break;
			}
			$content .= $c1;
		}

		$this->i--;

		$tag->tag = 'empty';
		$tag->content = $content;
	}

	function parseComment( &$tag ) {
		$this->i += 3;
		$content = '';
		while( true ) {
			$c1 = $this->html[ $this->i++ ];
			if( empty($c1 ) && $c1 != '0' ) {
				break;
			}

			if( $this->html[$this->i] == '-' && $this->isEqual('-->') ) 
				break;

			$content .= $c1;
		}
		$this->i += 3;

		$tag->tag = 'comment';
		$tag->content = $content;
	}

	function parsePhp( &$tag ) {

		$this->i += 1;
		$content = '';
		while( true ) {

			$c1 = $this->html[ $this->i++ ];
			if( empty($c1 ) && $c1 != '0' ) {
				break;
			}

			if( $this->isEqual('?>') ) 
				break;

			$content .= $c1;
		}
		$this->i += 2;

		$tag->tag = 'php';
		$tag->content = $content;	
	}

	function next1( &$tag ) {
		$c = @$this->html[$this->i++];
		if( empty($c) && $c != '0' ) return false;
		
		if( $c == '<') {
			if( $this->html[$this->i] == '!' && $this->isEqual('!--') ) {
				$this->parseComment( $tag );
				return true; 
			}

			if(  $this->html[ $this->i ] == ' ' ) {
				$this->i++;
				$this->parseContents( $tag );
				$tag->content = '<'.$tag->content;
				return true;
			}

			$this->ParseTag( $tag );
			return true; 
		} else {
			$this->parseContents( $tag );
			return true; 
		}

	}

	function next( &$tag ) {
		$ret = $this->next1( $tag );
		$this->current = $tag;
		return $ret;
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
		while( true ) {

			$c1 = $this->html[ $this->i++ ];
			if( empty($c1 ) && $c1 != '0' ) {
				break;
			}

			if( $c1 == '<' ) {
				if( $this->html[ $this->i] == '/' && $this->isEqual('/script') ) {
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
		while( true ) {
			$c1 = $this->html[ $this->i++ ];
			if( empty($c1 ) && $c1 != '0' ) {
				break;
			}

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

	function getTag( &$tag ) {

		if( ! $this->next( $tag ) )
			return false;

		if( $tag->tag == 'cdata' ) {
			$tag->content = $this->parseCData();
			return true;
		}

		if( $tag->tag[0] == '?' && substr($tag->tag,0,4) == '?xml' ) { $this->isXml = true; return true; }

		$hasNoEndTags = self::$hasNoEndTags;

		if( $this->isXml ) {
			unset( $hasNoEndTags[11] );
		}

		if( in_array( @$tag->tag, $hasNoEndTags ) ) return true;

		if( $tag->isEnd ) return true;

		if( $tag->tag == 'script' ) {
			$content = $this->parseScriptInner();
			$tag->content = $content;
		} else {
			$childrens = $this->parse( $tag );
			if( $childrens )
				$tag->childrens = $childrens;			
		}

		if( isset( $tag->tag ) && @$tag->tag == @$this->current->tag ) {
			return true;
		}

		while( true ) {
			$etag = new Tag;
			if( ! $this->next( $etag ) ) {
				break;
			}
			if( isset( $etag->tag ) && $tag->tag == @$etag->tag )
				break;
		}

		return true;
	}

	function parse( &$parent = '' ) {
		$tags = [];
		$stag = new Tag;
		$eq = 0;

		while( true ) {
			$tag = new Tag;

			if( ! $this->getTag( $tag ) ) {
				break;
			}

			if( $tag->isEnd && @$parent->tag == @$tag->tag ) break;

			if( isset( $tag->tag ) && $tag->tag == 'empty' && empty( trim($tag->content) ) )
				continue;

			if( ! $tag->isEnd ) {
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
		$f = new Find;
		return $f->find( $query, $this->document->childrens, $index );
	}

	function __construct( $html ) {

		$this->html = $html;
		$this->i = 0;

		$document = new Tag;
		$document->tag = 'document';
		$document->childrens = $this->parse( $document );

		\Pejman\DomParser\PQuery::$document = $document;
		$this->document = $document;
		    
	}

}