<?php
namespace Pejman\DomParser;

class Attrs {
	function classes() {
		return explode(" ", @$this->class);
	}
}