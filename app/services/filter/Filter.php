<?php

namespace app\services\filter;

abstract class Filter
{
    private $_name;

    public function __construct($name)
    {
        $this->_name = $name;
    }
    //Must return $value after treatment
    abstract public function filter($value, array $args = null, $globals);

    public function getFilterGlobalVars()
    {
        return array();
    }

    public function getName()
    {
        return $this->_name;
    }
}
