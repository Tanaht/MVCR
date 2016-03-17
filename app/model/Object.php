<?php

namespace app\model;
use app\config\Config;

class Object {
	public $test1;

	public function __construct(){
		$this->_test = "Test Successful !!!";
	}

	public function test2() {
		echo "second test";
	}
}