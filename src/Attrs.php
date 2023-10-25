<?php
namespace Pejman\DomParser;

class Attrs {

	public $classList = [];
	function __construct() {
		
	}

	function classes() {
		if( !empty( $this->class ) )
			return explode(" ", @$this->class);
		else
			return [];
	}

	function makeClassList() {
		$this->classList = $this->classes();	
	}

	
	function set( $key, $value ) {
		$this->$key = $value;
	}

	function get( $key ) {
		return @$this->$key;
	}

	function addClass( $class ) {
		if( $class && ! in_array( $class, $this->classList ) )
			$this->classList[] = $class;
	}

	function removeClass( $class ) {
		foreach( $this->classList as $k => $cls ) {
			if( $cls == $class )
				unset( $this->classList[ $k ] );
		}
	}

	function getClass() {
		return implode(" ", $this->classList );
	}

	function makeAttrsText() {
		$this->class = $this->getClass(); 

		$ret = '';
		$pre = '';

		foreach( $this as $attr => $value ) {
			if( is_string( $value ) ) {
				if( $attr == 'class' && ! $value )
					continue;
				
				$ret .= $pre.$attr.'="'.$value.'"';
				$pre = ' ';
			}
		}
		return $ret;
	}
}