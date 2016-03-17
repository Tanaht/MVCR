<?php

namespace app\router;

use app\view\View;
use app\controller\Controller;


//$moteurTpl = new TemplateRunner();
//echo $moteurTpl->show("test.tpl", array("title" => "Mon titre", "content" => "Mon contenu", "prix" => 5.02));

class Router {
	const VIEW = "view";
	const CTL = "controller";

	private $_view;
	private $_controller;
	private $_call;

	public function __construct() {
		$this->_call = null;
		$this->_view = new View($this);
		$this->_controller = new Controller($this, $this->_view);
	}

	public function run() {

		$this->when(array(
			),
			array(self::VIEW => "makeHomeView")
		)->when(array(
				"GET" => array("action" => "home")
			),
			array(self::VIEW => "makeHomeView")
		)->otherwise(array(self::VIEW => "pageNotFound"));

		$this->callMethods();
		$this->_view->render();
 	}


 	private function when(array $path, array $call) {
 		if($this->_call != null)
 			return $this;

 		if($this->resolve($path))
 			$this->_call = $call;
 		return $this;
 	}

 	private function otherwise(array $call) {
 		if($this->_call == null)
 			$this->_call = $call;
 	}

 	private function validatePost(array $array = null) {

 		foreach ($array as $key => $value) {
 			if(isset($_POST[$key]))
 				if($_POST[$key] == $value || $value == "$$")
 					continue;
 			return false;
 		}
 		return true;
 	}

 	private function validateGet(array $array = null) {

 		foreach ($array as $key => $value) {
 			if(isset($_GET[$key]))
 				if($_GET[$key] == $value || $value == "$$")
 					continue;
 			return false;
 		}
 		return true;
 	}

 	private function callMethods() {

 		if(array_key_exists(self::CTL, $this->_call)) {
			call_user_method($this->_call[self::CTL], $this->_controller);
		}

		if(array_key_exists(self::VIEW, $this->_call)) {
			call_user_method($this->_call[self::VIEW], $this->_view);
		}

		$this->logCall();
 	}

 	private function resolve(array $path) {
 		$postRequested = false;
 		$post = false;
 		$getRequested = false;
		$get = false;

		if( array_key_exists("POST", $path) ){
			$postRequested = true;
			if($path["POST"] == null)
				$get = $this->validatePost();
			else
				$get = $this->validatePost($path["POST"]);
		}

		if( array_key_exists("GET", $path) ){
			$getRequested = true;
			if($path["GET"] == null)
				$get = $this->validateGet();
			else
				$get = $this->validateGet($path["GET"]);
		}
		

		if($get == $getRequested && $post == $postRequested){
			return true;
		}

		return false;
 	}

 	private function logCall() {
 		echo "<script type='text/javascript'>";
 		if(array_key_exists(self::CTL, $this->_call)) {
			echo "console.log('controller: " . $this->_call[self::CTL] . "');"; 
		}

		if(array_key_exists(self::VIEW, $this->_call)) {
			echo "console.log('vue: " . $this->_call[self::VIEW] . "');"; 
		}
 		echo "</script>";
 	}
}