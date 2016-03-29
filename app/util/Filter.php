<?php

namespace app\util;

abstract class Filter {
	private $_name;

	public function __construct($name) {
		$this->_name = $name;
	}
	//Must return $value after treatment
	public abstract function filter($value, array $args = null, $globals);

	public function getName() {
		return $this->_name;
	}
}