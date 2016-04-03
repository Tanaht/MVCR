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

    const ROLES = array('ADMIN', 'MEMBER', 'USER');

    const DEFAULT_ROUTE = "pagenotfound";
    const DEFAULT_TEMPLATE = "squelette.tpl";


    const ROUTES = array(
    	'index' => array(
    		'path' => '/',
    		'controller' => array( 'app\controller\DefaultController' , 'login')
    	),
        'logout' => array(
            'path' => '/logout',
            'controller' => array( 'app\controller\DefaultController' , 'logout')
        ),
        'logon' => array(
            'path' => '/logon',
            'controller' => array( 'app\controller\DefaultController' , 'logon')
        ),
    	'cartes' => array(
    		'path' => '/cartes',
    		'controller' => array( 'app\controller\CarteController' , 'listeAll')
    	),
    	'ajouterCarte' => array(
    		'path' => '/cartes/ajouter',
    		'controller' => array( 'app\controller\CarteController' , 'create')
    	),
    	'carte' => array(
    		'path' => '/cartes/{id_carte:INT}',
    		'controller' => array( 'app\controller\CarteController' , 'viewOne')
    	),
    	'modifierCarte' => array(
    		'path' => '/cartes/{id_carte:INT}/modifier',
    		'controller' => array( 'app\controller\CarteController' , 'update')
    	),
    	'supprimerCarte' => array(
    		'path' => '/cartes/{id_carte:INT}/supprimer',
    		'controller' => array( 'app\controller\CarteController' , 'delete')
    	),
    	'mescartes' => array(
    		'path' => '/{username:STRING}/cartes',
    		'controller' => array( 'app\controller\CarteController' , 'listeMy')
    	),
    	'pagenotfound' => array(
    		'path' => '/erreur/404',
    		'controller' => array( 'app\controller\DefaultController' , 'error404')
    	), 
        'unhautorized' => array(
            'path' => '/erreur/401',
            'controller' => array( 'app\controller\DefaultController' , 'error401')
        ), 
    );
}
