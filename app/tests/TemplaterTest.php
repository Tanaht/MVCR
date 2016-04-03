<?php

namespace test;
//Doit être utilisé avec la commande php en console.
require_once './../../vendor/autoload.php';

use app\view\AbstractTemplate;
use app\view\Fragment;
use app\view\Template;
use app\view\TemplatesWrapper;
use app\services\filter\FilterProvider;
use app\services\filter\CurrencyFilter;
use app\services\filter\EscapeFilter;

function render(AbstractTemplate $template) {
	if($template->isValid()) {
		echo "\t" . $template->render() . "\n";
	}
	else{
		echo "\t[INVALIDE] => \n" . $template->forceRender() . "\n";
	}
}


echo "\n\n\n". "Contenu: 'Un Teste !!!':" . "\n";
$template = new Template('test.tpl');
render($template);

echo "\n\n\n". "Contenu: 'Hello {{ nom }} !!!':" . "\n";
$template = new Template("testVar.tpl");
render($template);
echo "\n\n\n". "inject('nom', 'Utilisateur')" . "\n";
$template->inject('nom', "Utilisateur");
render($template);

$template = new Template("testVar.tpl");
echo "\n\n\n". "inject('username', 'Utilisateur')" . "\n";
$template->inject('username', "Utilisateur");
render($template);

echo "\n\n\n". "Vous avez {{ argent | currency }} !!!" . " inject('argent', 12)" . "\n";
$template = new Template("testFilter.tpl");
$template->inject('argent', '12');
render($template);
echo "\n\n\n". "Ajout du filtre Currency" . "\n";
echo "Ajout du filtre Escape" . "\n";
FilterProvider::addFilter('currency', new CurrencyFilter());
FilterProvider::addFilter('escape', new EscapeFilter());

echo "\n\n\n". "Vous avez {{ argent | currency : dollar}} !!!
J'ai {{ '<h1>39</h1>' | currency | escape }}" . " inject('argent', '<h1>39</h1>')" . "\n";
$template = new Template("testFilterParam.tpl");
$template->inject('argent', '<h1>39</h1>');
render($template);

echo "\n\n############\n\t####Template Inflation:\n###########\n\n";

echo "\n\n\n". "Hello {{ nom }} !!! nom:inflateTemplate('test.tpl')". "\n";
$template = new Template('testVar.tpl');
$templatesWrapper = new TemplatesWrapper();
$templatesWrapper->addTemplate(new Template('test.tpl'));
$template->inflate('nom', $templatesWrapper);
render($template);


echo "\n\n\n". "Hello {{ nom }} !!! nom:inflateTemplate('testVar.tpl') inject('nom', 'Anonymous')". "\n";
$template = new Template('testVar.tpl');
$templatesWrapper = new TemplatesWrapper();
$templatesWrapper->addTemplate(new Template('testVar.tpl'));
$template->inflate('nom', $templatesWrapper);
$template->inject('nom', 'Anonymous');
render($template);



echo "\n\n\n". "Hello {{ nom }} !!! \nnom:inflateFragment('testVar.tpl') \ninjectFragment('nom', 'FragmentIndependant') \ninjectFragmentParent('nom', 'Anonymous')". "\n";
$template = new Template('testVar.tpl');
$templatesWrapper = new TemplatesWrapper();
$fragment = new Fragment('testVar.tpl');
$templatesWrapper->addTemplate($fragment);
$fragment->inject('nom', 'FragmentIndependant');
$template->inflate('nom', $templatesWrapper);
$template->inject('nom', 'Anonymous');
render($template);


echo "\n\n\n\t############Test de la portée des variables:" . "\n\n"; 
echo "\t####Données par défaut:\n";
$template = new Template('testGlobals.tpl');
$template->addGlobal('mere', 'MamanUnique');

$fragmentChild = new Fragment('fragment.tpl');
$fragmentChild->inject('nom', 'nom1');
$fragmentChild->inject('pere', 'pere1');
$templateChild = new Template('template.tpl');
$templateChild->inject('nom', 'nom1');
$templateChild->inject('pere', 'pere1');

$wrapper1 = new TemplatesWrapper();
$wrapper2 = new TemplatesWrapper();
$wrapper1->addTemplate($fragmentChild);
$wrapper2->addTemplate($templateChild);

$template->inflate('fragment', $wrapper1);
$template->inflate('template', $wrapper2);

render($template);
echo "\n\t####Injection de: ('nom' => 'nom2'):\n//Un fragment est un élement avec ses propres variables\n//Un template partage le scope de son père\n";
$template->inject('nom', 'nom2');
render($template);
echo "\n\t####Injection d'une variable globale: ('pere' => 'pere2'):\n//Une variable globale est prioritaire\n";
$template->addGlobal('pere', 'pere2');
render($template);


echo "\n\t####Injection d'une variable globale: ('mere' => 'MamanUnique2'):\n//Une variable globale ne peux pas être redéfinie\n";
$template->addGlobal('mere', 'MamanUnique2');
render($template);