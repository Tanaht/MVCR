<?php

namespace app\controller;

use app\router\Router;
use app\view\View;
use app\model\DatabaseHelper;
use app\model\Carte;

class Controller {
	private $_router;
	private $_view;

	public function __construct(Router $router, View $view) {
		$this->_view = $view;
		$this->_router = $router;
	}

	public function listeCartes() {
		$page = null;
		if(!isset($_GET["page"])) {
			$page = 1;
		}
		else {
			$page = $_GET["page"];
		}

		$bdd = DatabaseHelper::getBdd();

		$bdd->query("SELECT id_carte FROM cartes");

		$cartes = $bdd->forEachRow(array($this, "constructCarte"));
		$this->_view->showListeCartes($cartes);
	}

	public function constructCarte(&$row) {
		$row['carte'] = new Carte($row["id_carte"]);
	}

	public function vueCarte() {
		$this->_view->showCarte(new Carte($_GET["carte"]));
	}
}