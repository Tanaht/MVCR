<?php

namespace app\util;

use app\model\User;

class ShowFilter extends Filter
{
    public function __construct()
    {
        parent::__construct('showFor');
    }

    public function filter($before, array $args = null, $globals)
    {
        if (in_array($globals['user']->sudo, $args)) {
            return $before;
        }

        if ($globals['user']->_status == User::LOGON) {
            return '';
        }

        foreach ($args as $arg) {
            if ($globals['user']->getId() == $arg) {
                return $before;
            }
        }

        return '';
    }
}
