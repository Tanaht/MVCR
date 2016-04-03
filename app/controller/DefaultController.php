<?php

namespace app\controller;

use app\router\Request;
use app\view\Response;
use app\view\Template;
use app\view\Fragment;
use app\view\TemplatesWrapper;

class DefaultController extends AbstractController{
	
	public function login(Request $request, Response $response) {
		$response->view()->inject('title', 'YUDb - Home');
		
		$wrapper = $response->_w();

		$wrapper->addTemplate(new Template('frg/home.tpl'));
		$response->view()->inflate('content', $wrapper);
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