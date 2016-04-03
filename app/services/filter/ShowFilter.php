<?php

namespace app\services\filter;

use app\model\User;

class ShowFilter extends Filter
{
    public function __construct()
    {
        parent::__construct('showFor');
    }

    public function filter($before, array $args = null, $globals)
    {
        if (in_array($globals['user']->getRole(), $args)) {
            return $before;
        }

        if (!$globals['user']->connected()) {
            return '';
        }

        foreach ($args as $arg) {
            if ($globals['user']->utilisateur->id == $arg) {
                return $before;
            }
        }

        return '';
    }
}
