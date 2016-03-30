<?php
namespace Mvcr;

require_once '/vendor/autoload.php';


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

//Register Filters
$filters[] = new CurrencyFilter();
$filters[] = new ArianneFilter();
$filters[] = new InputFilter();
$filters[] = new HideFilter();
$filters[] = new ShowFilter();
$filters[] = new SelectedKeyFilter();
$filters[] = new EscapeFilter();
$filters[] = new IsEmptyFilter();

foreach ($filters as $key => $value) {
	FilterProvider::addFilter($value->getName(), $value);
}

$router = new Router();
$router->run();
