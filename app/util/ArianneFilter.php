<?php

namespace app\util;

class ArianneFilter extends Filter {

	public function __construct() {
		parent::__construct("constructArianne");
	}
	
	public function filter($before, array $args = null){
		$arianne = "";

		foreach ($before as $key => $value) {
			$arianne .= "<a href='" . $value["href"] . "'>" . $value["where"] . "</a>";

			//if !last
			if( (count($before) - 1) != $key )
				$arianne .= " - ";
		}
		
		return $arianne;
	}
}