<?php

namespace app\util;

class EscapeFilter extends Filter
{
    public function __construct()
    {
        parent::__construct('escape');
    }

    public function filter($before, array $args = null, $globals)
    {
        return htmlSpecialChars($before);
    }
}
