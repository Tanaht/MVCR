<?php

namespace app\view;
use app\services\TemplateRunner;

/*
	Un fragment est un template indépendant, il n'utilise pas le scope local de ses parents.
	cependant il peut injecter son scope local dans les enfants.
*/
class Fragment extends AbstractTemplate{

	//Injecte une donnée au hook prédéfinie
	//l'injection commence par les enfants et remonte, n'injecte rien si c'est un enfant.
	public function inject($hook, $data, $child=false) {
		if($child)
			return;

		foreach ($this->getTemplatesWrapperChilds() as $templatesWrapper) {
			$templatesWrapper->injectTemplates($hook, $data, $child);
		};

		if(in_array($hook, $this->searchedVars)) {
			if(!array_key_exists($hook, $this->hooks)) {
				$this->hooks[$hook] = $data;
				return;
			}

			if(!(is_object($this->hooks[$hook]) && get_class($this->hooks[$hook]) == TemplatesWrapper::class))
				$this->hooks[$hook] = $data;
		}
	}
}