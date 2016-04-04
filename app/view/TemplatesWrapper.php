<?php

namespace app\view;

class TemplatesWrapper
{
    private $templates;

    public function __construct()
    {
        $this->templates = array();
    }

    public function addTemplate(AbstractTemplate $template)
    {
        $this->templates[] = $template;
    }

    public function compiledTemplates()
    {
        $templateContainer = '';
        foreach ($this->templates as $template) {
            $templateContainer .= $template->render();
        }

        return $templateContainer;
    }

    public function inflateTemplates($hook, TemplatesWrapper $template)
    {
        foreach ($this->templates as $templateElement) {
            $templateElement->inflate($hook, $template);
        };
    }

    public function injectTemplates($hook, $data)
    {
        foreach ($this->templates as $templateElement) {
            $templateElement->inject($hook, $data, true);
        };
    }

    public function populateGlobals($globals)
    {
        foreach ($this->templates as $templateElement) {
            $templateElement->populateGlobals($globals);
        };
    }
}
