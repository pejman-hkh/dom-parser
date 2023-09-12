<?php
namespace Pejman\DomParser;

class Attrs {
	function classes() {
		return explode(" ", @$this->class);
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