<?php

namespace app\view;

use app\util\TemplateRunner;
use app\router\Router;
use app\model\Object;
//$moteurTpl = new TemplateRunner();
//echo $moteurTpl->show("test.tpl", array("title" => "Mon titre", "content" => "Mon contenu", "prix" => 5.02));
class View {
	private $basetitle;

	private $_router;
	public $_moteurTpl;
	//un hook est un crochet dans le template qui permet d'y inserer du texte ou un autre template
	private $_hook;
	private $_footer;
	private $_header;
	private $_templateFile;

	public function __construct(Router $router) {
		$this->basetitle = "YUDb";
		$this->_router = $router;
		$this->_moteurTpl = new TemplateRunner();
		$this->_templateFile = "squelette.tpl";
		
		$this->_hook = array(
			"title" => $this->basetitle . "", 
			"content" => ""
		);

		$this->_footer = array();
		$this->_header = array(
			"title" => "", 
			"arianne" => array(),
			"form" => ""
		);
		$this->addToArianne("?action=home", "Home");
	}


	public function render() {
		$this->makeHeader();
		$this->makeFooter();
		$file = $this->_moteurTpl->run($this->_templateFile, $this->_hook);


		//=====================CURL to W3C VALIDATOR
		$url = "http://validator.w3.org/nu/?out=json";

		$fp = fopen("js/validatorMessages.js", "w+");
		fwrite($fp, "var messages = ");

			$ch = curl_init($url);
			curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,10);
			curl_setopt($ch, CURLOPT_FILE, $fp);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-type: text/html; charset=utf-8"));
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $file);
			curl_exec($ch);
			curl_close($ch);
		
		fclose($fp);
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

	private function addToArianne($href, $where) {
		$this->_header["arianne"][] = array("href" => $href, "where" => $where);
	}

	public function makeRightHeader($template, $map = null) {
		if($map == null)
			$this->_header["form"] = $this->_moteurTpl->show("frg/" . $template);
		else
			$this->_header["form"] = $this->_moteurTpl->run("frg/" . $template, $map);
	}
	//=========================================================================================

	public function showAlert($alertMessage) {
		$this->_hook["content"] .= $this->_moteurTpl->run("frg/error/alert.tpl", $alertMessage);
	}

	public function showNewUserForm() {
		$this->addToArianne("#", "Inscription");
		$this->_hook["title"] = $this->basetitle . " - S'inscrire";
		$this->_hook["content"] .= $this->_moteurTpl->show("frg/form/inscription.tpl");
	}

	public function makeCarteForm($data) {
		$this->_hook["title"] = $this->basetitle . " - Créer une carte";
		$this->addToArianne("#", "Créer une carte");
		$this->_hook["content"] .= $this->_moteurTpl->run("frg/form/carteForm.tpl", $data);
	}

	public function makeHomeView() {
		$this->_hook["title"] = $this->basetitle . " - Home";
		$this->_hook["content"] .= $this->_moteurTpl->show("frg/home.tpl");
	}

	public function pageNotFound() {
		$this->_hook["title"] = "Erreur 404";
		$this->_hook["content"] .= $this->_moteurTpl->show("frg/error/404.tpl");
	}

	public function forbiddenAccess() {
		$this->_hook["title"] = "Accès refusé";
		$this->_hook["content"] .= $this->_moteurTpl->show("frg/error/forbiddenAccess.tpl");
	}

	public function showListeCartes($cartes) {
		$this->_hook["title"] = $this->basetitle . " - Cartes";
		$listeCartesContent = array("content" => "");
		

		//utilisé pour tester ce qui se passe quand beaucoup d'élements sont affiché:
		for($time = 0 ; $time < 1 ; $time++)
			foreach ($cartes as $key => $value) {
				$listeCartesContent["content"] .= $this->_moteurTpl->run("frg/carte_list_item.tpl", array("carte" => $value["carte"], "action" => "<a href='?carte=" . $value["carte"]->id . "'>Détails</a>"));
			}

			$listeCartesContent["title"] = "Liste des cartes";
		$this->addToArianne("?action=cartes", "Les Cartes");
		$this->_hook["content"] .= $this->_moteurTpl->run("frg/listeCartes.tpl", $listeCartesContent);
	}

	public function showCarte($carte) {
		$this->addToArianne("?action=cartes", "Les Cartes");
		$this->addToArianne("?carte=" . $carte->id, $carte->nom);

		$this->_hook["title"] = $this->basetitle . " - " . $carte->nom;
		$this->_hook["content"] .= $this->_moteurTpl->run("frg/carte_detail.tpl", array("carte" => $carte));
	}
}