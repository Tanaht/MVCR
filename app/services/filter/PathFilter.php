<?php
/**
 * Created by PhpStorm.
 * User: Antoine
 * Date: 03/04/2016
 * Time: 15:31.
 */

namespace app\services\filter;

use exceptions\FilterInvalidException;

class PathFilter extends Filter
{
    public function __construct()
    {
        parent::__construct('path');
    }

    public function filter($before, array $args = null, $globals)
    {
        if (count($args) == 0) {
            throw new FilterInvalidException("Le filtre path nécéssite un argument qui n'a pas été fourni");
        }

        $username = '';
        $carte = $args[0];

        if ($globals['user']->connected()) {
            $username = strtolower($globals['user']->utilisateur->username);
        }
        $pathsReplace = array(
            array('search' => '{username:STRING}', 'replace' => $username),
            array('search' => '{idCarte:INT}', 'replace' => $carte),
        );

        foreach ($pathsReplace as $path) {
            $before = str_replace($path['search'], $path['replace'], $before);
        }

        return $globals['baseuri'].$before;
    }
}
