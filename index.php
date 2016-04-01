<?php
namespace Mvcr;

require_once './vendor/autoload.php';


use app\router\Router;
use app\util\FilterProvider;
use app\util\Filter;
use app\util\CurrencyFilter;
use app\util\ArianneFilter;
use app\util\InputFilter;
use app\util\HideFilter;
use app\util\ShowFilter;
use app\util\SelectedKeyFilter;
use app\util\EscapeFilter;
use app\util\IsEmptyFilter;
use app\util\ToListFilter;

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

$router = new Router();
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
