<?php

namespace app\services\filter;

use app\model\User;

class HideFilter extends Filter
{
    public function __construct()
    {
        parent::__construct('hideFor');
    }

    public function filter($before, array $args = null, $globals)
    {
        if (in_array($globals['user']->sudo, $args)) {
            return '';
        }

        if ($globals['user']->_status == User::LOGON) {
            return $before;
        }

        foreach ($args as $arg) {
            if ($globals['user']->getId() == $arg) {
                return '';
            }
        }

        return $before;
    }
}
