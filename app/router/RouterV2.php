<?php

namespace app\router;

use app\view\Response;
use app\controller\Controller;
use app\model\User;

use urisolver\UriSolver;
use app\config\Config;

class RouterV2 {
	
	private $request;
    private $response;
    private $_controller;
	public function __construct() {
		$this->request = new Request();
		$this->request->setRouteName(Config::DEFAULT_ROUTE);
        $this->response = new Response();
        $this->response->setUser($this->request->user);
        $this->response->addGlobal('router', Config::ROUTES);
        $this->response->addGlobal('baseuri', Config::BASE_URI);
	}
	public function run() {
        //$this->debug();

		$uriSolver = new UriSolver($this->request->getUrl());

        foreach (Config::ROUTES as $routeName => $route) {
        	if($uriSolver->matche($route['path']))
        		$this->request->setRouteName($routeName);
        }

        $this->_controller = Config::ROUTES[$this->request->getRouteName()]['controller'][0];
        $this->_controller = new $this->_controller($this);


        call_user_func(Config::ROUTES[$this->request->getRouteName()]['controller'], $this->request, $this->response);
        $this->response->send($this->request);
        //$this->debug();
    }


    private function debug() {
    	var_dump($_SERVER["PATH_INFO"], $_SERVER['REQUEST_URI'], $_SERVER['SERVER_NAME'],$_GET, $_POST);
    }
}