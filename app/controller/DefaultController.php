<?php

namespace app\controller;

use app\config\DatabaseHelper;
use app\router\Request;
use app\view\Response;
use app\view\Template;
use formBuilder\Form;

class DefaultController extends AbstractController
{
    public function home(Request $request, Response $response)
    {
        $response->view()->inject('title', parent::getTitle('Accueil'));
        $contentWrapper = $response->_w();
        $contentWrapper->addTemplate(new Template('frg/home.tpl'));
        $response->view()->inflate('content', $contentWrapper);
    }

    public function logout(Request $request, Response $response)
    {
        $request->user->logout();
        $request->handleResponse($response);

        $response->responseRedirect('/');
    }

    public function inscription(Request $request, Response $response)
    {
        $inscriptionForm = new Form('inscriptionForm', 'inscription.tpl');

        $inscriptionForm->add('login', null, true);
        $inscriptionForm->add('password', null, true);
        $inscriptionForm->add('mail', null, true);

        $inscriptionForm->handleRequest($request);
        $inscriptionForm->rememberThisForm();
        if ($request->getMethod() == Request::METHOD_POST) {
            if ($inscriptionForm->isValid()) {
                $validContent = $this->checkInscription($inscriptionForm);
                if (!$validContent) {
                    $response->responseRedirect($request->getUrl());
                }

                $request->user->register($inscriptionForm->get('login'), $inscriptionForm->get('password'), $inscriptionForm->get('mail'));
                $request->authenticateUser($inscriptionForm->get('login'), $inscriptionForm->get('password'));
                $response->responseRedirect('/cartes');
            } else {
                $response->responseRedirect($request->getUrl());
            }
        }

        $contentWrapper = $response->_w();
        $contentWrapper->addTemplate($inscriptionForm->getFormFragment());

        $response->view()->inflate('content', $contentWrapper);
        $response->view()->inject('title', 'Inscription');
    }

    private function checkInscription(Form $form)
    {
        $return = true;
        if (filter_var($form->get('mail'), FILTER_VALIDATE_EMAIL) == false) {
            $form->setError('mail', 'Email non valide');
            $return = false;
        }

        $bdd = DatabaseHelper::getBdd();

        $bdd->query("SELECT username FROM utilisateurs WHERE username = '".trim($form->get('login'))."'");
        $rows = $bdd->execute();

        foreach ($rows as $row) {
            if ($row['username'] == trim($form->get('login'))) {
                $form->setError('username', "L'utilisateur ".$row['username'].' existe déja !!!');
                $return = false;
            }
        }

        return $return;
    }

    public function error404(Request $request, Response $response)
    {
        $contentTemplate = $response->_w();

        $contentTemplate->addTemplate(new Template('frg/error/404.tpl'));

        $response->view()->inject('title', parent::getTitle('Page Inconnu'));
        $response->view()->inflate('content', $contentTemplate);
    }

    public function error401(Request $request, Response $response)
    {
        $contentTemplate = $response->_w();

        $contentTemplate->addTemplate(new Template('frg/error/401.tpl'));

        $response->view()->inject('title', parent::getTitle('Accès Refusé'));
        $response->view()->inflate('content', $contentTemplate);
    }

    public function apropos(Request $request, Response $response)
    {
        $contentTemplate = $response->_w();

        $contentTemplate->addTemplate(new Template('frg/apropos.tpl'));

        $response->view()->inject('title', parent::getTitle('A Propos'));
        $response->view()->inflate('content', $contentTemplate);
    }
}
