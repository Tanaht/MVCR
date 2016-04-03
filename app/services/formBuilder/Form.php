<?php

namespace formBuilder;

use app\router\Request;
use app\view\Fragment;

class Form {
	private $name;
	private $inputs;
    private $formFragment;
    private $injectionDataForm;

    public function __construct($name, $formPath)
    {
        $this->name = $name;
        $this->formFragment = new Fragment('frg/form/' . $formPath);
        $this->inputs = array();

        $this->injectionDataForm = array(
            'name' => $name,
            'inputs' => array()
        );
    }

    public function add($name, $value=null, $required = false) {
        $this->inputs[$name] = new Input($name);
        $this->injectionDataForm['inputs'][$name] = array();
        $this->injectionDataForm['inputs'][$name]['name'] = $name;
        $this->injectionDataForm['inputs'][$name]['name'] = $name;
        $this->injectionDataForm['inputs'][$name]['value'] = '';
        $this->injectionDataForm['inputs'][$name]['required'] = '';
        if($value != null) {
            $this->inputs[$name]->setValue($value);
            $this->injectionDataForm['inputs'][$name]['value'] = $value;
        }

        if($required) {
            $this->inputs[$name]->required();
            $this->injectionDataForm['inputs'][$name]['required'] = 'required';
        }
    }

    public function get($name) {
        if(!isset($this->inputs[$name]))
            return null;
        return $this->inputs[$name]->getValue();
    }

    public function getFormFragment() {
        $this->formFragment->inject('form', $this->injectionDataForm);
        return $this->formFragment;
    }

    public function isValid() {
        foreach ($this->inputs as $input) {
            if(!$input->isValid())
                return false;
        }

        return true;
    }

    public function isFormLogin() {
        return $this->get('_password') != null && $this->get('_username') != null;
    }


    /*
     * le formulaire va prendre en charge la requete si le type de requête est POST,
     * les valeurs du formulaire vont être mis à jour.
    */
    public function handleRequest(Request $request) {
        if($request->getMethod() != Request::METHOD_POST)
            return;

        $this->populatePOSTArgs();
        if(!$request->user->connected() && $this->isFormLogin()) {

            if(!$this->isValid()) {
                $request->redirectTo($request->getUrl());//TODO: Translate url into KeyMap
                return;
            }

            foreach ($request->user->getUsers($this->get('_username')) as $utilisateur) {
                if ($request->user->authenticate($utilisateur, $this->get('_password')))
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

    private function populatePOSTArgs() {
        foreach ($this->inputs as $input) {
            if(isset($_POST[$input->getName()])) {
                $input->setValue($_POST[$input->getName()]);
            }
        }
    }

}