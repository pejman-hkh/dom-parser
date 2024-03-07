<?php
namespace Pejman\DomParser;

class Parser {

	private $tag;
	private $html;
	private $i;
	private $current;
	public $document;
	public $length;
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
				$start = $this->i;
				$len = 0;
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
					
					$len++;
				}
				$value = substr( $this->html, $start, $len );
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
		$tag = $this->tag;
		if( $this->html[ $this->i ] == '!' && $this->isEqual('![CDATA[') ) {
			$this->i+= 8;

			$tag->tag = 'cdata';
			return;							
		}

		if( @$this->html[ $this->i+1 ] == '/' ) $this->i++;

		$name = '';
		$attrs = [];
		$start = $this->i;
		$len = 0;
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
			
			$len++;
			//$name .= $c1;
		}

		$name = substr( $this->html, $start, $len );

		$tag->tag = $name;
	
		$tag->attrs = @$attrs; 
		$tag->isEnd = false;
		if( $name[0] == '/' ) {
			$tag->isEnd = true;
			$tag->tag = substr($name,1);
		}

		if( $name[$len-1] == '/' ) {
			$tag->tag = substr( $name, 0, -1);
		}
		return true;
	}

	function parseContents( $first = '' ) {
		$tag = $this->tag;		
		$this->i--;

		$content = '';
		$len = 0; 
		$start = $base = $this->i;
		// while( true ) {
		// 	$c1 = $this->html[ $this->i++ ];

		// 	if( empty($c1 ) && $c1 != '0' ) {
		// 		break;
		// 	}

		// 	if( $c1 == '<' ) {
		// 		break;
		// 	}
		// 	$len++;
		// 	//$content .= $c1;
		// }

		$pos = strpos( $this->html, '<', $start );
		if( ! $pos ) {
			$pos = $this->length;
		}

		$len =  $pos-$base;
		$this->i =  $pos+1;

		$content = substr( $this->html, $start, $len );

		$this->i--;


		$tag->tag = 'empty';
		$tag->content = ($first?:'').$content;
		return true;
	}

	function parseComment() {
		$tag = $this->tag;
		$this->i += 3;
		$content = '';
		$start = $base = $this->i;
		$len = 0;
		// while( true ) {
		// 	$c1 = $this->html[ $this->i++ ];
		// 	if( empty($c1 ) && $c1 != '0' ) {
		// 		break;
		// 	}

		// 	if( $this->html[$this->i] == '-' && $this->isEqual('-->') ) 
		// 		break;

		// 	$len++;
		// 	//$content .= $c1;
		// }


		while( true ) {

			$pos = strpos( $this->html, '-', $start );
			if( ! $pos ) {
				$this->i = $this->length;
				break;
			}
	
			$len =  $pos-$base;
			$this->i =  $pos+1;
		
			if( $this->isEqual('->') ) {
				break;
			}
		
			$start = $pos+1;
		}

		$content = substr( $this->html, $base, $len );
		$this->i += 2;

		$tag->tag = 'comment';
		$tag->content = $content;
		return true;
	}

	function parsePhp() {
		$tag = $this->tag;
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

	function parseScriptInner() {
		$content = '';
		$start = $base = $this->i;
		$len = 0;
		// while( true ) {

		// 	$c1 = $this->html[ $this->i++ ];
		// 	if( empty($c1 ) && $c1 != '0' ) {
		// 		break;
		// 	}

		// 	if( $c1 == '<' ) {
		// 		if( $this->html[ $this->i] == '/' && $this->isEqual('/script') ) {
		// 			break;
		// 		}
		// 	}

		// 	$len++;
		// 	//$content .= $c1;
		// }

		while( true ) {

			$pos = strpos( $this->html, '<', $start );
			if( ! $pos ) {
				$this->i = $this->length;
				break;
			}
	
			$len =  $pos-$base;
			$this->i =  $pos+1;
			if( $this->isEqual('/script') ) {
				break;
			}

			$start = $pos+1;
		}

		$content = substr( $this->html, $base, $len );
	
		$this->i+= 8;
		return $content;
	}

	function parseCData() {
		$content = '';
		$start = $this->i;
		$len = 0;
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

			//$content .= $c1;
			$len++;
		}

		$content = substr( $this->html, $start, $len );
	
		$this->i+= 2;
		return $content;
	}


	function next1() {
		$tag = $this->tag;
		$c = @$this->html[$this->i++];
		if( empty($c) && $c != '0' ) return false;
		
		if( $c == '<') {
			if( $this->html[$this->i] == '!' && $this->isEqual('!--') ) {
				$this->parseComment();
				return true; 
			}

			if(  $this->html[ $this->i ] == ' ' ) {
				$this->i++;
				$tag->content = '<'.$tag->content;
				return $this->parseContents( '<' );
			}

			return $this->ParseTag();
	
		} else {
			return $this->parseContents();

		}

	}

	function next() {
		$tag = $this->tag;
		$ret = $this->next1();
		$this->current = &$tag;
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

	function getTag() {
		$tag = $this->tag;
		if( ! $this->next() )
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

		if(  @$tag->tag == @$this->current->tag ) {
			return true;
		}

		while( true ) {
			$etag = new Tag;
			$this->tag = $etag;
			if( ! $this->next() ) {
				break;
			}
			if( $tag->tag == @$etag->tag )
				break;
		}
		$this->tag = $tag;
		
		return true;
	}

	function parse( $parent = '' ) {
		$tags = [];
		$stag = new Tag;
		$eq = 0;

		while( true ) {
			$tag = new Tag;
			$this->tag = $tag;

			if( ! $this->getTag() ) {
				break;
			}

			if( $tag->isEnd && @$parent->tag == @$tag->tag ) break;

			if( $tag->tag == 'empty' && empty( trim($tag->content) ) )
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
		$this->length = strlen( $html );

		$document = new Tag;
		$document->tag = 'document';
		$document->childrens = $this->parse( $document );

		\Pejman\DomParser\PQuery::$document = $document;
		$this->document = $document;
		    
	}

}