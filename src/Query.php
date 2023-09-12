<?php
namespace Pejman\DomParser;
class Query {
	function getQueries() {
		$a = '';
		while( @$c = $this->query[ $this->iq++ ] ) {
			if( $c == ' ')
				break;
			$a .= $c;
		}
		return $a;
	}

	function getStr() {
		$a = '';
		while( @$c = $this->mQuery[ $this->miq++ ] ) {
			if( in_array( $c, ['#','.','[', '=', ']'] ) )
				break;
			$a .= $c;
		}
		$this->miq--;
		return $a;
	}

	function parseQuery() {
		$ret = [];

		while( $c = @$this->mQuery[ $this->miq++ ] ) {
			if( $c == '.' ) {
				$ret['class'] = $this->getStr();
			} elseif( $c == '#' ) {
				$ret['id'] = $this->getStr();
			} elseif( $c == ']' ) {
				$this->miq++;
			} elseif( $c == '[' ) {

				$key = $this->getStr();
				$this->miq++;
	
				$ret[ $key ] = $this->getStr();
			} else {
				$this->miq--;
				$ret['tag'] = $this->getStr();
			}
		}

		return $ret;
	}	
}