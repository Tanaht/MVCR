<?php

namespace app\view;

use app\router\SecurityUser;
use exceptions\FilterInvalidException;
use exceptions\TemplateInvalidException;
use app\router\Request;

class Response {
	private $httpCode;
    private $template;
    private $_globals;

    public function __construct() {
        $this->_globals = array();

		$this->httpCode = 200;
		$this->template = new Template();
		$header = $this->_w();
		$header->addTemplate(new Template('frg/header.tpl'));
		$footer = $this->_w();
		$footer->addTemplate(new Template('frg/footer.tpl'));

		$this->template->inflate('header', $header);
		$this->template->inflate('footer', $footer);
	}

    public function addGlobal($key, $value)
    {
        $this->_globals[$key] = $value;
    }

    public function populateGlobal() {
        foreach ($this->_globals as $key => $value) {
            $this->template->addGlobal($key, $value);
        }
    }

	public function responseRedirect($redirectPath) {
        $this->httpCode = 302;
		$this->setHeaders();
		header("Location: $redirectPath");
		exit;
    }

	public function view(){
		return $this->template;
	}

	public function send(Request $request){
        $this->populateGlobal();
		$request->handleResponse($this);

		try{
			if(!$this->template->isValid()) {
                throw new TemplateInvalidException("Ce template n'est pas valide", $this->template);
            }
		}
		catch(TemplateInvalidException $e) {
			$this->template = new Template("exceptions/exception.tpl");
            $this->populateGlobal();
			$this->template->injects(array('message' => $e->getMessage(), 'trace' => $e->getTrace(), 'traceAsString' => $e->getTraceAsString()));
			$this->httpCode = 500;
		}
        catch(FilterInvalidException $e) {
            $this->template = new Template("exceptions/exception.tpl");
            $this->populateGlobal();
            $this->template->injects(array('message' => $e->getMessage(), 'trace' => $e->getTrace(), 'traceAsString' => $e->getTraceAsString()));
            $this->httpCode = 500;
        }

		$this->setHeaders();

        try {
            $render = $this->template->render();
        }
        catch(FilterInvalidException $e) {
            $this->template = new Template("exceptions/exception.tpl");
            $this->populateGlobal();
            $this->template->injects(array('message' => $e->getMessage(), 'trace' => $e->getTrace(), 'traceAsString' => $e->getTraceAsString()));
            $this->httpCode = 500;
            $this->template->render();
            return;
        }

        echo $render;
	}

	public function setUser(SecurityUser $user) {
		$this->addGlobal('user', $user);
	}

	//generate a wrapper for templates
	public function _w() {
		return new TemplatesWrapper();
	}

	private function setHeaders() {
		http_response_code($this->httpCode);
	}
}