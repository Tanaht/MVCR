<?php

namespace app\router;

use app\view\Response;
use app\view\Template;
use app\controller\AbstractController;
use formBuilder\Form;

class Request
{
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';

    private $_uriParams;
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
    public function __construct()
    {
        session_start();
        $this->method = $_SERVER['REQUEST_METHOD'];

        if (!isset($_SERVER['PATH_INFO'])) {
            $this->url = '/';
        } else {
            $this->url = $_SERVER['PATH_INFO'];
        }

        $this->routeName = '';
        $this->user = AbstractController::getUser();
        $this->reloadUser();
        $this->redirectPath = '/cartes';
    }

    public function redirectTo($path)
    {
        $this->redirectPath = $path;
    }

    public function setRouteName($name)
    {
        $this->routeName = $name;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function getRouteName()
    {
        return $this->routeName;
    }

    public function getUrl()
    {
        return $this->url;
    }

    private function reloadUser()
    {
        if (isset($_SESSION['user'])) {
            $this->user = \unserialize($_SESSION['user']);
        } else {
            return $this->user;
        }
    }

    public function authenticateUser($name, $password)
    {
        foreach ($this->user->getUsers($name) as $utilisateur) {
            if ($this->user->authenticate($utilisateur, $password)) {
                break;
            }
        }
    }

    public function denyForRoles(array $roles, Response $response)
    {
        if ($this->user->getRole() == 'ADMIN') {
            return;
        }
        if (in_array($this->user->getRole(), $roles)) {
            $response->responseRedirect('/erreur/401');
        }
    }

    public function allowForAuthenticateUser($username, Response $response)
    {
        if (!$this->user->connected()) {
            $response->responseRedirect('/erreur/401');
        }

        if ($this->user->getRole() == 'ADMIN') {
            return;
        }

        if (0 != strcmp(trim(strtolower($this->user->utilisateur->username)), trim(strtolower($username)))) {
            echo $this->user->utilisateur->username . " != " . $username;
            exit;
            $response->responseRedirect('/erreur/401');
        }
    }

    //On ne gère que GET et POST, on part du principe que OPTION, DELETE, PUT, HEAD, ne seront jamais envoyé.
    public function handleResponse(Response $response)
    {
        $formLoginWrapper = $response->_w();
        $formLogin = new Form('formLogin', 'logon.tpl');
        $formLogin->add('_username', null, true);
        $formLogin->add('_password', null, true);
        $formLogin->handleRequest($this);

        $_SESSION['user'] = serialize($this->user);

        if ($this->method == self::METHOD_GET) {
            $wrapper = $response->_w();
            if (!$this->user->connected()) {
                $formLoginWrapper->addTemplate($formLogin->getFormFragment());
                $response->view()->inflate('formLogin', $formLoginWrapper);
            } else {
                $formLoginWrapper->addTemplate(new Template('frg/form/logoff.tpl'));
                $response->view()->inject('formLogin', $formLoginWrapper);
            }
        }
    }

    public function getParam($paramKey)
    {
        if (!isset($this->_uriParams[$paramKey])) {
            return;
        }

        return $this->_uriParams[$paramKey];
    }
    public function setParamUri($getMatchedRouteParams)
    {
        $this->_uriParams = $getMatchedRouteParams;
    }

    public function getRedirect()
    {
        return $this->redirectPath;
    }
}
