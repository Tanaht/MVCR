<?php
namespace Mvcr;

require_once '/vendor/autoload.php';


use app\router\Router;
use app\util\FilterProvider;
use app\util\Filter;
use app\util\CurrencyFilter;
use app\util\ArianneFilter;


//Register Filters
$filters[] = new CurrencyFilter();
$filters[] = new ArianneFilter();

foreach ($filters as $key => $value) {
	FilterProvider::addFilter($value->getName(), $value);
}


$router = new Router();
$router->run();