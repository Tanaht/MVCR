<?php

namespace Mvcr;

require_once './vendor/autoload.php';

use app\router\AbstractRouter;
use app\router\Router;
use app\router\RouterV2;
use app\services\filter\FilterProvider;
use app\services\filter\CurrencyFilter;
use app\services\filter\ArianneFilter;
use app\services\filter\InputFilter;
use app\services\filter\HideFilter;
use app\services\filter\ShowFilter;
use app\services\filter\SelectedKeyFilter;
use app\services\filter\EscapeFilter;
use app\services\filter\IsEmptyFilter;
use app\services\filter\ToListFilter;

//Register Filters
$filters[] = new CurrencyFilter();
$filters[] = new ArianneFilter();
$filters[] = new InputFilter();
$filters[] = new HideFilter();
$filters[] = new ShowFilter();
$filters[] = new SelectedKeyFilter();
$filters[] = new EscapeFilter();
$filters[] = new IsEmptyFilter();
$filters[] = new ToListFilter();

foreach ($filters as $key => $value) {
    FilterProvider::addFilter($value->getName(), $value);
}


$router = new RouterV2();
$router->run();

/*
$search = searchString

SELECT c.* FROM cartes c JOIN utilisateurs user USING(id_utilisateur) WHERE user.nom like "%$search%"
UNION
SELECT c.* FROM cartes c WHERE attaque like "%search%"
UNION
SELECT c.* FROM cartes c WHERE defense like "%search%"
UNION
SELECT c.* FROM cartes c WHERE description like "%search%"
UNION
SELECT c.* FROM cartes c WHERE niveau like "%search%"
UNION
SELECT c.* FROM cartes c WHERE c.id_carte in (SELECT c.id_carte FROM cartes c JOIN carte_types ct using(c.id_carte) JOIN type t using(ct.id_type) WHERE t.nom like "%$search%")
UNION
SELECT c.* FROM cartes c JOIN categories cat USING(id_categorie) WHERE cat.nom like "%$search%"
UNION
SELECT c.* FROM cartes c JOIN effets ef USING(id_effet) WHERE ef.nom like "%$search%"
UNION
SELECT c.* FROM cartes c JOIN attributs attr USING(id_attribut) WHERE attr.nom like "%$search%"
*/
