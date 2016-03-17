<?php

namespace app\controller;

use app\router\Router;
use app\view\View;
use app\model\DatabaseHelper;

class Controller {
	private $_router;
	private $_view;

	public function __construct(Router $router, View $view) {
		$this->_view = $view;
		$this->_router = $router;
	}
}