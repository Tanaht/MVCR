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
	private $_footer;
	private $_header;
	private $_templateFile;

	public function __construct(Router $router) {
		$this->_router = $router;
		$this->_moteurTpl = new TemplateRunner();
		$this->_templateFile = "squelette.tpl";
		
		$this->_hook = array(
			"title" => "WebSite", 
			"arianne" => "<span><a href='?action=home'>Home</a></span>", 
			"content" => "", 
			"menu" => "", 
			"script" => ""
		);

		$this->_footer = array();
		$this->_header = array("title" => "");
	}


	public function render() {
		$this->makeHeader();
		$this->makeFooter();
		$file = $this->_moteurTpl->run($this->_templateFile, $this->_hook);


		//=====================CURL to W3C VALIDATOR
		$ch = curl_init("http://validator.w3.org/nu/?out=json");

		$fp = fopen("js/validatorMessages.js", "w+");
		fwrite($fp, "var messages = ");

		curl_setopt($ch, CURLOPT_FILE, $fp);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-type: text/html; charset=utf-8"));
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $file);
		curl_exec($ch);
		curl_close($ch);
		fclose($fp);
		$this->_hook["script"] = "<script src='js/validatorMessages.js'></script>";
		//=====================
		
		echo $this->_moteurTpl->run($this->_templateFile, $this->_hook);
	}

	private function makeFooter() {
		$this->_hook["footer"] = $this->_moteurTpl->run("frg/footer.tpl", $this->_footer);
	}

	private function makeHeader() {
		$this->_header["title"] = $this->_hook["title"];
		$this->_hook["header"] = $this->_moteurTpl->run("frg/header.tpl", $this->_header);
	}

	//=========================================================================================
	public function makeHomeView() {
		$this->_hook["title"] = "WebSite - Home";
		$this->_hook["content"] = $this->_moteurTpl->show("frg/home.tpl");
	}

	public function pageNotFound() {
		$this->_hook["title"] = "Erreur 404";
		$this->_hook["content"] = $this->_moteurTpl->show("frg/error/404.tpl");
	}

	public function showListeCartes($cartes) {
		$listeCartesContent = array("content" => "");
		foreach ($cartes as $key => $value) {
			$listeCartesContent["content"] .= $this->_moteurTpl->run("frg/carte_list_item.tpl", array("carte" => $value["carte"], "action" => "<a href='?carte=" . $value["carte"]->id . "'>Détails</a>"));
		}

		$this->_hook["arianne"] = "<span><a href='?action=home'>Home</a> - <a href='?action=cartes'>Les Cartes</a></span>";
		$this->_hook["content"] = $this->_moteurTpl->run("frg/listeCartes.tpl", $listeCartesContent);
	}

	public function showCarte($carte) {
		$this->_hook["arianne"] = "<span><a href='?action=home'>Home</a> - <a href='?action=cartes'>Les Cartes</a> - <a href='?carte=" . $carte->id ."'>" . $carte->nom . "</a></span>";

		$this->_hook["content"] = $this->_moteurTpl->run("frg/carte_detail.tpl", array("carte" => $carte));
	}
}