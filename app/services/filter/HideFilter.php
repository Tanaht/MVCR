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
        if (in_array($globals['user']->getRole(), $args)) {
            return '';
        }

        if (!$globals['user']->connected()) {
            return $before;
        }

        foreach ($args as $arg) {
            if ($globals['user']->utilisateur->id == $arg) {
                return '';
            }
        }

        return $before;
    }
}
