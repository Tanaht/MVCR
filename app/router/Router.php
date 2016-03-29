<?php

namespace app\router;

use app\view\View;
use app\controller\Controller;
use app\model\User;

class Router {

	const stringParam = "##";
	const intParam = "#int#";
	const floatParam = "#float#";
	const arrayParam = "#array#";

	const VIEW = "view";
	const CTL = "controller";

	private $_view;
	private $_controller;
	private $_call;

	public function __construct() {
		$this->_call = null;
		$this->_view = new View($this);
		$this->_controller = new Controller($this, $this->_view);
	}

	/**
		@see Router::when(array $path, array $call)
		Le routage se fait grâce à la méthode privée when qui est inspiré du routage d'angularJS.

		Le routeur à deux fonctions principales, qui se ressemble, mais reste très différente
			Trouver la bonne méthode à appeler en fonction des paramètres de routage (POST, GET) 
				et du type d'utilisateur (SUDO => "USER, MEMBER, ADMIN")
			Effectuer une première validation de formulaire: "stringParam, intParam, floatParam, arrayParam" 
				en fonction des clés des tableaux POST et GET

	*/
	public function run() {
		/*echo "<pre>";
		var_export($_POST);
		echo "</pre>";*/
		$this->when(array(
				"POST" => null,
				"GET" => null
			),
			array(self::VIEW => "makeHomeView")
		)->when(array(
				"GET" => array("action" => "home")
			),
			array(self::VIEW => "makeHomeView")
		)->when(array(
				"GET" => array(
					"action" => "cartes", 
					"page" => self::intParam
					/*
						TODO: diminuer le nombre de cartes qui s'affichent sur une seule page pour ne pas surcharger le site
					*/
				)
			),
			array(self::CTL => "listeCartes")
		)->when(array(
				"GET" => array(
					"action" => "mescartes", 
					"page" => self::intParam
					/*
						TODO: diminuer le nombre de cartes qui s'affichent sur une seule page pour ne pas surcharger le site
					*/
				),
				"SUDO" => array(User::MEMBER, User::ADMIN)
			),
			array(self::CTL => "listeMesCartes")
		)->when(array(
				"GET" => array(
					"action" => "mescartes"
				),
				"SUDO" => array(User::MEMBER, User::ADMIN)
			),
			array(self::CTL => "listeMesCartes")
		)->when(array(
				"GET" => array("action" => "create"),
				"SUDO" => array(User::ADMIN, User::MEMBER)
			),
			array(self::CTL => "creerCarteForm")
		)->when(array(
				"POST" => array(
					"create" => "card",
					"nom" => self::stringParam,
					"niveau" => self::intParam,
					"categorie" => self::intParam,
					"effet" => self::intParam,
					"attribut" => self::intParam,
					"types" => self::arrayParam,
					"description" => self::stringParam,
					"attaque" => self::stringParam,
					"defense" => self::stringParam
				),
				"SUDO" => array(User::ADMIN, User::MEMBER)
			),
			array(self::CTL => "creerCarte")
		)->when(array(
				"GET" => array("carte" => self::intParam),
				"POST" => array(
					"update" => "card",
					"nom" => self::stringParam,
					"niveau" => self::intParam,
					"categorie" => self::intParam,
					"effet" => self::intParam,
					"attribut" => self::intParam,
					"types" => self::arrayParam,
					"description" => self::stringParam,
					"attaque" => self::stringParam,
					"defense" => self::stringParam
				),
				"SUDO" => array(User::ADMIN, User::MEMBER)
			),
			array(self::CTL => "updateCarte")
		)->when(array(
				"GET" => array("action" => "cartes")
			),
			array(self::CTL => "listeCartes")
		)->when(array(
				"GET" => array("carte" => self::intParam, "carteAction" => "more")
			),
			array(self::CTL => "vueCarte")
		)->when(array(
				"GET" => array("carte" => self::intParam, "carteAction" => "update"),
				"SUDO" => array(User::MEMBER, User::ADMIN)
			),
			array(self::CTL => "updateCarteForm")
		)->when(array(
				"GET" => array("carte" => self::intParam, "carteAction" => "delete"),
				"SUDO" => array(User::MEMBER, User::ADMIN)
			),
			array(self::CTL => "deleteCarte")
		)->when(array(
				"GET" => array("inscription" => "")
			),
			array(self::VIEW => "showNewUserForm")
		)->when(array(
				"POST" => array(
					"inscrire" => "", 
					"username" => self::stringParam, 
					"email" => self::stringParam, 
					"password" => self::stringParam
				)
			),
			array(self::CTL => "newUser")
		)->when(array(
				"POST" => array(
					"logon" => "", 
					"username" => self::stringParam, 
					"password" => self::stringParam
				)
			),
			array(self::CTL => "logOnUser", self::VIEW => "makeHomeView")
		)->when(array(
				"POST" => array("logoff" => "")
			),
			array(self::CTL => "logOffUser", self::VIEW => "makeHomeView")
		)->otherwise(array(self::VIEW => "pageNotFound"));

		$this->callMethods();
		$this->_view->render();
 	}


 	/*
		$path: La route à vérifier. Idéalement, la route devrait se présenté sous-forme d'url.
			Il s'agit d'une liste de couple (clé/valeur)
				clé possible: POST/GET/(SUDO: cas à part)
				les clé POST et GET ont pour valeur une représentation des tableaux $_GET et $_POST, que la requête HTTP actuel doit posseder pour valider cette route.
		$path: une liste de Callable, qui vont impacter la page html, par le biais du controller, ou de la vue, si cette route est empruntée.

		return: $this (Pour enchainer les appels à when)

 	*/
 	private function when(array $path, array $call) {
 		if($this->_call != null)
 			return $this;

 		if($this->resolve($path)) {
 			$isAuthorized = true;

 			if( array_key_exists("SUDO", $path) ){
				$isAuthorized = $this->checkAuthorization($path["SUDO"]);
			}

			if($isAuthorized)
 				$this->_call = $call;
 		}
 		return $this;
 	}

 	/*
 		Si aucune route n'a été trouvé, on définis un callable qui va impacter la vue, peu importe l'état de l'application (une page 404 par exemple)
 	*/
 	private function otherwise(array $call) {
 		if($this->_call == null)
 			$this->_call = $call;
 	}

 	/*
 		Valide ou Invalide la représentation schématique du tableau $_POST de la méthode when

 		return true ou false 
 	*/
 	private function validatePost(array $array = null) {
 		if($array == null) 
 			return empty($_POST);

 		foreach ($array as $key => $value) {
 			if(isset($_POST[$key])) {
 				if($_POST[$key] == $value || $value == self::stringParam)
 					continue;

 				if($value == self::intParam && filter_var($_POST[$key], FILTER_VALIDATE_INT) != false)
 					continue;

 				if($value == self::arrayParam && is_array($_POST[$key]))
 					continue;

 				if($value == self::floatParam && filter_var($_POST[$key], FILTER_VALIDATE_FLOAT) != false)
 					continue;

 			}
 			/*else{
 				echo $key . " is not set<br/>";
 			}*/
 			return false;
 		}
 		return true;
 	}

 	/*
 		Valide ou Invalide la représentation schématique du tableau $_GET de la méthode when

 		return true ou false 
 	*/
 	private function validateGet(array $array = null) {
 		if($array == null)
 			return empty($_GET);

 		foreach ($array as $key => $value) {
 			if(isset($_GET[$key])) {
 				if($_GET[$key] == $value || $value == self::stringParam)
 					continue;

 				if($value == self::intParam && filter_var($_GET[$key], FILTER_VALIDATE_INT) != false)
 					continue;

 				if($value == self::arrayParam && is_array($_POST[$key]))
 					continue;

 				if($value == self::floatParam && filter_var($_GET[$key], FILTER_VALIDATE_FLOAT) != false)
 					continue;
 			}

 			return false;
 		}
 		return true;
 	}

 	/*
 		Appels des méthodes qui vont impacter le rendu de la page html
 	*/
 	private function callMethods() {

 		if(array_key_exists(self::CTL, $this->_call)) {
			call_user_method($this->_call[self::CTL], $this->_controller);
		}

		if(array_key_exists(self::VIEW, $this->_call)) {
			call_user_method($this->_call[self::VIEW], $this->_view);
		}

		$this->logCall();
 	}

 	/*
 		Résoud une route.
 		@see Router::when(array $path, array $call);

 		return true ou false;
 	*/
 	private function resolve(array $path) {
 		$postRequested = false;
 		$post = false;
 		$getRequested = false;
		$get = false;

		if( array_key_exists("POST", $path) ){
			$postRequested = true;
			$post = $this->validatePost($path["POST"]);
		}

		if( array_key_exists("GET", $path) ){
			$getRequested = true;
			$get = $this->validateGet($path["GET"]);
		}

		if($get == $getRequested && $post == $postRequested){
			return true;
		}

		return false;
 	}

 	/*
 		Vérifie l'authorisation de l'utilisateur (connecté ou non) par rapport à la liste des types de membres authorisée d'une route

 		TODO: implémentation trop rapide -> trouver une solution pour éviter de recourir à une méthode de la vue (forbiddenAccess) en 'dur'
 	*/
 	private function checkAuthorization($sudoers) {
 		$authorized = $this->_controller->checkAuthorization($sudoers);

 		if($authorized)
 			return true;
 		else {
 			$this->_call = array(self::VIEW => "forbiddenAccess");
 			return false;
 		}
 	}


 	//TODO: supprimer ou afficher à la place du script
 	private function logCall() {
 		echo "<script type='text/javascript'>";
 		if(array_key_exists(self::CTL, $this->_call)) {
			echo "console.log('controller: " . $this->_call[self::CTL] . "');"; 
		}

		if(array_key_exists(self::VIEW, $this->_call)) {
			echo "console.log('vue: " . $this->_call[self::VIEW] . "');"; 
		}
 		echo "</script>";
 	}
}