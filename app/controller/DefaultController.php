<?php

namespace app\controller;

use app\router\Request;
use app\view\Response;
use app\view\Template;
use formBuilder\Form;

class DefaultController extends AbstractController{
	
	public function login(Request $request, Response $response) {
		$response->view()->inject('title', parent::getTitle("Accueil"));
		$contentWrapper = $response->_w();
		$contentWrapper->addTemplate(new Template('frg/home.tpl'));
		$response->view()->inflate('content', $contentWrapper);
	}

	public function logout(Request $request, Response $response) {

	}

	public function logon(Request $request, Response $response) {

	}

	public function error404(Request $request, Response $response) {

	}

	public function error401(Request $request, Response $response) {

	}
}