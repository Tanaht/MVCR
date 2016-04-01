<?php

namespace app\services\filter;

class FilterProvider
{
    private static $filters;

    public static function addFilter($name, Filter $filter)
    {
        if (self::$filters == null) {
            self::$filters[$name] = $filter;

            return;
        }

        if (!array_key_exists($name, self::$filters)) {
            self::$filters[$name] = $filter;
        }
    }

    public static function getFilter($name)
    {
        if (self::$filters == null) {
            return;
        }

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
