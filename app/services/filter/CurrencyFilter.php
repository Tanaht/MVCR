<?php

namespace app\services\filter;

class CurrencyFilter extends Filter
{
    public function __construct()
    {
        parent::__construct('currency');
    }

    public function filter($before, array $args = null, $globals)
    {
        $devise = '€';
        if (count($args) != 0) {
            $devise = $args[0];
        }

        if ($devise == '€') {
            $before .= ' '.$devise;
        }

        if ($devise == '$') {
            $before = $devise.' '.$before;
        }

        return $before;
    }

    public function getFilterGlobalVars()
    {
        return array('euro' => '€', 'dollar' => '$');
    }
}
