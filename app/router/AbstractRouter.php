<?php

namespace app\router;

use app\view\View;
use app\controller\Controller;
use app\model\User;

abstract class AbstractRouter {
	protected $_view;
    protected $_controller;
    protected $_call;

	public function __construct()
    {
        $this->_call = null;
        $this->_view = new View($this);
        $this->_controller = new Controller($this, $this->_view);
    }

	public abstract function run();
}