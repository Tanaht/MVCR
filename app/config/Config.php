<?php

namespace app\config;

class Config
{
    //TEMPLATE DIRECTORY
    const TPL_DIR = 'tpl/';

    //ASSETS DIRECTORY
    const ASSETS_DIRECTORY = 'upload/';

    //PDO SIGNIN

    const DB_HOST = 'localhost';
    const DB_NAME = '21302782_dev';
    const DB_USER = '21302782';
    const DB_PWD = 'sg8stA9g1565';

    const DEFAULT_ROUTE = '/erreur/404';
    const DEFAULT_TEMPLATE = 'squelette.tpl';
    const BASE_URI = '/MVCR';

    const ROUTES = array(
        'home' => array(
            'path' => '/',
            'controller' => array('app\controller\DefaultController', 'home'),
        ),
        'logout' => array(
            'path' => '/logout',
            'controller' => array('app\controller\DefaultController', 'logout'),
        ),
        'inscription' => array(
            'path' => '/inscription',
            'controller' => array('app\controller\DefaultController', 'inscription'),
        ),
        'cartes' => array(
            'path' => '/cartes',
            'controller' => array('app\controller\CarteController', 'listeAll'),
        ),
        'apropos' => array(
            'path' => '/about',
            'controller' => array('app\controller\DefaultController', 'apropos'),
        ),
        'ajouterCarte' => array(
            'path' => '/cartes/ajouter',
            'controller' => array('app\controller\CarteController', 'create'),
        ),
        'carte' => array(
            'path' => '/cartes/{idCarte:INT}',
            'controller' => array('app\controller\CarteController', 'viewOne'),
        ),
        'modifierCarte' => array(
            'path' => '/cartes/{idCarte:INT}/modifier',
            'controller' => array('app\controller\CarteController', 'update'),
        ),
        'supprimerCarte' => array(
            'path' => '/cartes/{idCarte:INT}/supprimer',
            'controller' => array('app\controller\CarteController', 'delete'),
        ),
        'mescartes' => array(
            'path' => '/{username:STRING}/cartes',
            'controller' => array('app\controller\CarteController', 'listeMy'),
        ),
        'pagenotfound' => array(
            'path' => '/erreur/404',
            'controller' => array('app\controller\DefaultController', 'error404'),
        ),
        'unhautorized' => array(
            'path' => '/erreur/401',
            'controller' => array('app\controller\DefaultController', 'error401'),
        ),
    );
}
