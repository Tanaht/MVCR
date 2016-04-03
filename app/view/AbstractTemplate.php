<?php

namespace app\view;
use app\services\TemplateRunner;
use app\config\Config;

abstract class AbstractTemplate {
	protected $runner;
	//chemin du template;
	protected $templatePath;
	//variables à définir du template
	protected $searchedVars;

	//crochets d'insertion (Correspond au variables de template) dans le template
	protected $hooks;

	private $_globals;
	//TODO: Debug function to suppress
	/*public function getNeeded() {
		return $this->searchedVars;
	}

	public function getHooks() {
		return $this->hooks;
	}*/
	public function __construct($templatePath = Config::DEFAULT_TEMPLATE, TemplateRunner $runner = null) {
		if($runner != null)
			$this->runner = $runner;
		else
			$this->runner = new TemplateRunner();

		$this->templatePath = $templatePath;
		$this->searchedVars = $this->runner->searchVars($this->templatePath);	 
		$this->hooks = array();

		$this->_globals = array();

	}

	public function addGlobal($key, $value) {
		if(!isset($this->_globals[$key]))
            $this->_globals[$key] = $value;
	}

	public function populateGlobals($globals) {
		foreach ($this->getTemplatesWrapperChilds() as $templatesWrapper) {
			$templatesWrapper->populateGlobals($this->_globals);
		};

		$this->_globals = $globals;
	}


	//Ajoute un template au hook prédéfini
	//l'injection commence par les enfants et remonte.
	public function inflate($hook, TemplatesWrapper $templates)  {
		foreach ($this->getTemplatesWrapperChilds() as $templatesWrapper) {
			$templatesWrapper->inflateTemplates($hook, $templates);
			//Fonctionne aussi apparemment, le $this dans un foreach équivaut a l'élement du foreach
			//$this->inflateTemplates($hook, $templates);
		};

		if(in_array($hook, $this->searchedVars)) {	
			$this->hooks[$hook] = $templates;
		}
	}

	//$map est de la forme array('$hook' => '$templates');
	public function inflates(array $map) {
		foreach ($map as $hook => $templates) {
			$this->inflate($hook, $templates);
		}
	}


	//Injecte une donnée au hook prédéfinie
	public abstract function inject($hook, $data, $child = false);

	//$map est de la forme array('$hook' => '$data');
	public function injects(array $map) {
		foreach ($map as $hook => $data) {
			$this->inject($hook, $data);
		}
	}

	//Compile le SuperTemplate obtenu
	public function render() {
		$compiledHooks = array();

		$this->populateGlobals($this->_globals);

		foreach ($this->hooks as $hook => $content) {

			if(is_object($content) && get_class($content) == TemplatesWrapper::class)
				$compiledHooks[$hook] = $content->compiledTemplates();
			else
				$compiledHooks[$hook] = $this->hooks[$hook];
		}

		$this->runner->addGlobals($this->_globals);
		return $this->runner->run($this->templatePath, $compiledHooks);
	}

	//Valide un template en fonction de ses hooks et des variables recherché.
	public function isValid() {
		foreach ($this->searchedVars as $searchedVar) {
			if(!array_key_exists($searchedVar, $this->hooks))
				return false;
		}
		return true;
	}

	public function forceRender() {
		return $this->render();
	}

	//retourne un array de tout les enfants qui sont des TemplatesWrapper
	protected function getTemplatesWrapperChilds() {
		$childs = array();
		foreach ($this->hooks as $hook => $content) {
			if(is_object($content) && get_class($content) == TemplatesWrapper::class)
				$childs[] = $content;
		}

		return $childs;
	}
}