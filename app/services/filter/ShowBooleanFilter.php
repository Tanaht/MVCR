<?php

namespace app\services\filter;

use app\model\User;

class ShowBooleanFilter extends Filter
{
    public function __construct()
    {
        parent::__construct('showBoolean');
    }

    public function filter($before, array $args = null, $globals)
    {
        return $before ? 'TRUE' : 'FALSE';
    }
}
