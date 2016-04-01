<?php
//list : 'nom'

namespace app\services\filter;

class ToListFilter extends Filter
{
    public function __construct()
    {
        parent::__construct('list');
    }

    public function filter($before, array $args = null, $globals)
    {
        $listValue = $args[0];
        $list = '<ul>';
        $listContent = '';
        foreach ($before as $listItem) {
            if (is_object($listItem)) {
                $listContent .= '<li>'.$listItem->$listValue.'</li>';
            }

            if (is_array($listItem)) {
                $listContent .= '<li>'.$listItem[$listValue].'</li>';
            }
        }

        $list .= $listContent;
        $list .= '</ul>';

        return $list;
    }
}
