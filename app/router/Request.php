<?php

namespace app\router;
use app\view\Response;
use app\view\Template;
use app\view\TemplatesWrapper;
use app\controller\AbstractController;
use app\config\DatabaseHelper;


class Request {
	const METHOD_GET = 'GET';
	const METHOD_POST = 'POST';

	protected $method;
	protected $url;
	protected $forms;
	protected $routeName;
    private $redirectPath;
	public $user;

	/*
		initie le type de requete envoyé au serveur
		-> recupère l'utilisateur, ou l'authentifie...
	*/
	public function __construct() {
		session_start();
		$this->method = $_SERVER["REQUEST_METHOD"];
		
		if(!isset($_SERVER["PATH_INFO"]))
			$this->url = "/";
		else
			$this->url = $_SERVER["PATH_INFO"];
		
		$this->routeName = "";
		$this->reloadUser();
        $this->redirectPath = 'cartes';
	}

    public function redirectTo($path) {
        $redirectPath = $path;
    }

	public function setRouteName($name) {
		$this->routeName = $name;
	}

	public function getMethod()
	{
		return $this->method;
	}

	public function getRouteName() {
		return $this->routeName;
	}

	public function getUrl() {
		return $this->url;
	}

	private function reloadUser() {
		if(isset($_SESSION['user']))
			$this->user = \unserialize($_SESSION['user']);
		else {
			$this->user = AbstractController::getUser();
		}
	}

	//On ne gère que GET et POST, on part du principe que OPTION, DELETE, PUT, HEAD, ne seront jamais envoyé.
	public function handleResponse(Response $response) {
		if($this->method == self::METHOD_POST) {
			$response->responseRedirect($this->redirectPath);
		}

		if($this->method == self::METHOD_GET) {
			$wrapper = $response->_w();
			if(!$this->user->connected()) {
				$wrapper->addTemplate(new Template('frg/form/logon.tpl'));
				$response->view()->inject('formLogin', $wrapper);
			}
			else {
				$wrapper->addTemplate(new Template('frg/form/logoff.tpl'));
				$response->view()->inject('formLogin', $wrapper);
			}
		}
	}
}