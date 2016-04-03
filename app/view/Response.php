<?php

namespace app\view;

use app\router\SecurityUser;
use exceptions\TemplateInvalidException;
use app\router\Request;

class Response {
	private $httpCode;
	private $template;
	public function __construct() {
		$this->httpCode = 200;
		$this->template = new Template();
		$header = $this->_w();
		$header->addTemplate(new Template('frg/header.tpl'));
		$footer = $this->_w();
		$footer->addTemplate(new Template('frg/footer.tpl'));

		$this->template->inflate('header', $header);
		$this->template->inflate('footer', $footer);
	}

	public function responseRedirect($redirectPath) {
        $this->httpCode = 302;
    }

	public function view(){
		return $this->template;
	}

	public function send(Request $request){
		$request->handleResponse($this);

		try{
			if(!$this->template->isValid())
				throw new TemplateInvalidException("Ce template n'est pas valide", $this->template);
		}
		catch(TemplateInvalidException $e) {
			$this->template = new Template("exceptions/exception.tpl");
			$this->template->injects(array('message' => $e->getMessage(), 'trace' => $e->getTrace(), 'traceAsString' => $e->getTraceAsString()));
			$this->httpCode = 500;
		}
		echo $this->template->render();
	}

	public function setUser(SecurityUser $user) {
		$this->template->addGlobal('user', $user);
	}

	//generate a wrapper for templates
	public function _w() {
		return new TemplatesWrapper();
	}
}