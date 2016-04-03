<?php

namespace formBuilder;

use app\router\Request;

class Form {
	private $name;
	private $inputs;

    public function __construct($name)
    {
        $this->name = $name;
        $this->inputs = array();
    }

    public function getName()
    {
        return $this->name;
    }

    public function add($type, $name, $values = null) {
        return $this->inputs[$name] = new Input($type, $name, $values);
    }


    /*public function handleRequest(Request $request) {
		if(!$request->user->connected() && $this->form->isFormLogin()) {
			$users = $request->getUsers($request->form->login());

			foreach ($users as $user) {
				if($request->user->authenticate($user, $request->form->password()))
					break;
			}
		}
	}*/

    public function get($name) {
        if(array_key_exists($name, $this->inputs))
            return $this->inputs[$name]->getInputValue;

        return null;
    }

    public function isFormLogin() {
        return array_key_exists('_password', $this->inputs) && array_key_exists('_username', $this->inputs);
    }

    public function isValid() {
        foreach ($this->inputs as $input) {
            if(!$input->isValid())
                return false;
        }
        return true;
    }

    public function handleRequest(Request $request) {
        if($request->getMethod() != Request::METHOD_POST)
            return;
        
        if(!$request->user->connected() && $this->isFormLogin()) {
            foreach ($request->user->getUsers($this->get('_username')) as $user) {
                if ($request->user->authenticate($user, $request->get('_password')))
                    break;
            }

            if($request->user->connected()) {
                $request->redirectTo('index');
            }
            else {
                $request->redirectTo('login');
            }
        }
    }

}