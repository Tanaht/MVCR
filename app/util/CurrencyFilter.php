<?php

namespace app\util;

class CurrencyFilter extends Filter {

	public function __construct() {
		parent::__construct("currency");
	}
	
	public function filter($before, array $args = null){
		return $before . " €";
	}
}