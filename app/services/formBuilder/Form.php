<?php

namespace formBuilder;

use app\router\Request;
use app\view\Fragment;

class Form
{
    private $name;
    private $inputs;
    private $formFragment;
    private $injectionDataForm;

    public function __construct($name, $formPath)
    {
        $this->name = $name;
        $this->formFragment = new Fragment('frg/form/'.$formPath);
        $this->inputs = array();

        $this->injectionDataForm = array(
            'name' => $name,
            'errors' => array(),
            'inputs' => array(),
        );
    }

    public function add($name, $value = null, $required = false)
    {
        $this->inputs[$name] = new Input($name);
        $this->injectionDataForm['inputs'][$name] = array();
        $this->injectionDataForm['errors'][$name] = '';
        $this->injectionDataForm['inputs'][$name]['name'] = $name;
        $this->injectionDataForm['inputs'][$name]['name'] = $name;
        $this->injectionDataForm['inputs'][$name]['value'] = '';
        $this->injectionDataForm['inputs'][$name]['required'] = '';
        if ($value != null) {
            $this->inputs[$name]->setValue($value);
            $this->injectionDataForm['inputs'][$name]['value'] = $value;
        }

        if ($required) {
            $this->inputs[$name]->required();
            $this->injectionDataForm['inputs'][$name]['required'] = 'required';
        }

        if ($name == 'types[]') {
            echo $this->inputs[$name]->getName().' Il existe<br>';
        }
    }

    public function get($name)
    {
        if (!isset($this->inputs[$name])) {
            return;
        }

        return $this->inputs[$name]->getValue();
    }

    public function setError($name, $error)
    {
        $this->injectionDataForm['errors'][$name] = $error;
    }

    public function getFormFragment()
    {
        if (isset($_SESSION[$this->injectionDataForm['name']]) && !$this->isFormLogin()) {
            $this->injectionDataForm = unserialize($_SESSION[$this->injectionDataForm['name']]);
        }
        $this->formFragment->inject('form', $this->injectionDataForm);

        return $this->formFragment;
    }

    public function isValid()
    {
        foreach ($this->inputs as $input) {
            if (!$input->isValid()) {
                return false;
            }
        }

        return true;
    }

    public function isFormLogin()
    {
        $user = $this->get('_username');
        $pass = $this->get('_password');

        return isset($user) && isset($pass);
    }

    /*
     * le formulaire va prendre en charge la requete si le type de requête est POST,
     * les valeurs du formulaire vont être mis à jour.
    */
    public function handleRequest(Request $request)
    {
        if ($request->getMethod() != Request::METHOD_POST) {
            return;
        }

        $this->populatePOSTArgs();
        if ((!$request->user->connected()) && $this->isFormLogin()) {
            $request->authenticateUser($this->get('_username'), $this->get('_password'));

            if ($request->user->connected()) {
                $request->redirectTo('/cartes');
            } else {
                $request->redirectTo('/');
            }

            return;
        }
    }

    public function rememberThisForm()
    {
        $_SESSION[$this->injectionDataForm['name']] = serialize($this->injectionDataForm);
    }

    private function populatePOSTArgs()
    {
        foreach ($this->inputs as $input) {
            if (isset($_POST[$input->getName()])) {
                $input->setValue($_POST[$input->getName()]);
                $this->injectionDataForm['inputs'][$input->getName()]['value'] = $input->getValue();
            }
            elseif(isset($_FILES[$input->getName()])) {
                $input->setValue($_FILES[$input->getName()]);
                $this->injectionDataForm['inputs'][$input->getName()]['value'] = $input->getValue();
            }
        }
    }
}
