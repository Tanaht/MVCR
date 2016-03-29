<?php

namespace app\util;

class HideFilter extends Filter {

	public function __construct() {
		parent::__construct("hideFor");
	}
	
	public function filter($before, array $args = null){
		if(in_array($_SESSION["sudo"], $args))
			return "";

		return $before;
	}
}