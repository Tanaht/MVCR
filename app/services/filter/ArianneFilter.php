<?php

namespace app\services\filter;

class ArianneFilter extends Filter
{
    public function __construct()
    {
        parent::__construct('constructArianne');
    }

    public function filter($before, array $args = null, $globals)
    {
        $arianne = '';

        foreach ($before as $key => $value) {
            $arianne .= "<a href='".$value['href']."'>".FilterProvider::getFilter('escape')->filter($value['where'], null, null).'</a>';

            //if !last
            if ((count($before) - 1) != $key) {
                $arianne .= ' - ';
            }
        }

        return $arianne;
    }
}
