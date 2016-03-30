<?php

namespace app\util;

class IsEmptyFilter extends Filter {

	public function __construct() {
		parent::__construct("empty");
	}
	
	public function filter($before, array $args = null, $globals){
		$emptyEqual = $args[0];
		$valueToCompare = $args[1];
		$errorMessage = $args[2];
		
		if($valueToCompare == "this")
			$valueToCompare = $before;
		
		if($emptyEqual == "true" && empty($valueToCompare)) {
			return $errorMessage;
		}
		
		if($EmptyEqual == "false" && !empty($valueToCompare)) {
			return $errorMessage;
		}
		
		return $before;
	}
}
