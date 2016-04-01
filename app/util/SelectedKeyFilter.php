<?php

namespace app\util;

class SelectedKeyFilter extends Filter
{
    public function __construct()
    {
        parent::__construct('selectedKey');
    }

    public function filter($before, array $args = null, $globals)
    {
        $selectedKey = $args[0];

        foreach ($before as $key => $value) {
            if ($before[$key]['key'] == $selectedKey) {
                $before[$key]['selected'] = true;
            }
        }

        return $before;
    }
}
