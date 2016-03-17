<?php
namespace Mvcr;

require_once '/vendor/autoload.php';


use app\router\Router;
use app\util\FilterProvider;
use app\util\Filter;
use app\util\CurrencyFilter;


//Register Filters
$filters[] = new CurrencyFilter();

foreach ($filters as $key => $value) {
	FilterProvider::addFilter($value->getName(), $value);
}


$router = new Router();
$router->run();