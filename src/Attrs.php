<?php
namespace Pejman\DomParser;

class Attrs {
	function classes() {
		return explode(" ", @$this->class);
	}

	function set( $key, $value ) {
		$this->$key = $value;
	}

	function get( $key ) {
		return $this->$key;
	}

	function makeAttrsText() {
		$ret = '';
		$pre = '';
		foreach( $this as $attr => $value ) {
			$ret .= $pre.$attr.'="'.$value.'"';
			$pre = ' ';
		}
		return $ret;
	}
}