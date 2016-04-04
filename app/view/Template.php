<?php

namespace app\view;

class Template extends AbstractTemplate
{
    //Injecte une donnée au hook prédéfinie,
    //l'injection commence par les enfants et remonte.
    public function inject($hook, $data, $child = false)
    {
        foreach ($this->getTemplatesWrapperChilds() as $templatesWrapper) {
            $templatesWrapper->injectTemplates($hook, $data, $child);
        };

        //On modifie la valeur injecté que si ce n'est pas un TemplatesWrapper
        if (in_array($hook, $this->searchedVars)) {
            if (!array_key_exists($hook, $this->hooks)) {
                $this->hooks[$hook] = $data;

                return;
            }

            if (!(is_object($this->hooks[$hook]) && get_class($this->hooks[$hook]) == TemplatesWrapper::class)) {
                $this->hooks[$hook] = $data;
            }
        }
    }
}
