<?php

namespace app\util;

class IsEmptyFilter extends Filter {

	public function __construct() {
		parent::__construct("empty");
	}
	
	public function filter($before, array $args = null, $globals){
		$emptyEqual = $args[0];
		$equalTo = $args[1];
		$errorReturn = $args[2];
		return $before . " €";
	}
}
