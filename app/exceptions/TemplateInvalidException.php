<?php

namespace exceptions;

use app\view\AbstractTemplate;

class TemplateInvalidException extends \Exception
{
    public function __construct($message, AbstractTemplate $template)
    {
        $message .= "<br/>\n".$template->render();
        parent::__construct($message);
    }
}
