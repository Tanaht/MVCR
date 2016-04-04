<?php

namespace app\router;

use app\view\Response;
use app\model\User;
use app\view\Template;
use urisolver\UriSolver;
use app\config\Config;

class RouterV2
{
    private $request;
    private $response;
    private $_controller;
    public function __construct()
    {
        $this->request = new Request();
        $this->request->setRouteName(Config::DEFAULT_ROUTE);
        $this->response = new Response(Config::BASE_URI);
        $this->response->setUser($this->request->user);
        $this->response->addGlobal('router', Config::ROUTES);
        $this->response->addGlobal('baseuri', Config::BASE_URI);
        $this->response->addGlobal('assets', Config::BASE_URI.'/'.Config::ASSETS_DIRECTORY);
    }
    public function run()
    {
        $successRouting = false;
        $uriSolver = new UriSolver($this->request->getUrl());

        foreach (Config::ROUTES as $routeName => $route) {
            if ($uriSolver->matche($route['path'])) {
                $successRouting = true;
                $this->request->setRouteName($routeName);
                $this->request->setParamUri($uriSolver->getMatchedRouteParams());
                break;
            }
        }

        if (!$successRouting) {
            $this->response->responseRedirect(Config::DEFAULT_ROUTE);
        }

        $class = Config::ROUTES[$this->request->getRouteName()]['controller'][0];
        $this->_controller = new $class($this);

        $this->request->handleResponse($this->response);
        try{
            call_user_func(array($this->_controller, Config::ROUTES[$this->request->getRouteName()]['controller'][1]), $this->request, $this->response);
        }
        catch(\Exception $e) {
            $tpl = new Template('exceptions/exception.tpl');
            $tpl->injects(array("baseuri" => Config::BASE_URI, "message" => $e->getMessage(), "traceAsString" => $e->getTraceAsString()));
            echo $tpl->render();
            exit;
        }

        $this->response->send($this->request);

        //$this->debug();
    }

    private function debug()
    {
        var_dump($this->request->getRouteName(), $_SESSION['inscriptionForm']);
    }
}
