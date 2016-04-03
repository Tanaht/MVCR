<?php

namespace app\services\filter;

class FilterProvider
{
    private static $filters = array();

    public static function addFilter($name, Filter $filter)
    {
        if (!array_key_exists($name, self::$filters))
            self::$filters[$name] = $filter;
    }

    public static function getFilter($name)
    {
        if (array_key_exists($name, self::$filters)) {
            return self::$filters[$name];
        }

        return;
    }

    public static function getFilters()
    {
        return self::$filters;
    }
}
