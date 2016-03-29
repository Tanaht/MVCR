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

//Register Filters
$filters[] = new CurrencyFilter();
$filters[] = new ArianneFilter();
$filters[] = new InputFilter();
$filters[] = new HideFilter();

foreach ($filters as $key => $value) {
	FilterProvider::addFilter($value->getName(), $value);
}

$router = new Router();
$router->run();