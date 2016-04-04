<?php

namespace app\controller;

use app\router\RouterV2;
use app\model\User;
use app\config\Config;

abstract class AbstractController
{
    private $_router;
    private $_user;

    public function __construct(RouterV2 $router)
    {
        $this->_router = $router;
    }

    protected function path($pathname)
    {
        if (!array_key_exists($pathname, Config::ROUTES)) {
            return Config::ROUTES[Config::DEFAULT_ROUTE]['path'];
        }

        return Config::ROUTES[$pathname]['path'];
    }

    public static function getUser()
    {
        return new User();
    }

    public function getTitle($title)
    {
        return 'YUDb - '.$title;
    }
}
