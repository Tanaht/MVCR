<?php

namespace app\view;

use app\util\TemplateRunner;
use app\router\Router;
use app\model\Object;
//$moteurTpl = new TemplateRunner();
//echo $moteurTpl->show("test.tpl", array("title" => "Mon titre", "content" => "Mon contenu", "prix" => 5.02));
class View {

	private $_router;
	private $_moteurTpl;
	//un hook est un crochet dans le template qui permet d'y inserer du texte ou un autre template
	private $_hook;
	private $_templateFile;

	public function __construct(Router $router) {
		$this->_router = $router;
		$this->_moteurTpl = new TemplateRunner();
		$this->_templateFile = "squelette.tpl";
		$this->_hook = array("title" => "WebSite", "content" => "", "menu" => "");
	}

	public function makeError404View() {
		$this->_hook["content"] = $this->_moteurTpl->show("error/404.tpl");
	}

	public function makeHomeView() {
		$this->_hook["title"] = "WebSite - Home -" . $_GET['action'];
		$this->_hook["content"] = $this->_moteurTpl->show("home.tpl");
	}

	public function pageNotFound() {
		$this->_hook["test1"] = new Object();
		$this->_hook["test2"] = array("test1" => "arrayTest1 successfull");
		$this->_hook["title"] = "Erreur 404";
		$this->_hook["content"] = $this->_moteurTpl->show("frg/error/404.tpl");
	}

	public function render() {
		echo $this->_moteurTpl->show($this->_templateFile, $this->_hook);
	}
}