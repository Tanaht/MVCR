<?php

namespace app\services;

use app\config\Config;
use app\services\filter\FilterProvider;

//preg_match_all : {{\s*[A-Za-z]*\s*}}
//preg_match_all : {{\s*[A-Za-z0-9]+\s*(:\s*[A-Za-z0-9]+\s*)*}} => filter functionality is working

/*
    faiblesse de cette façon de proceder: 
        impossibilité d'utilisé la méthode htmlspecialchars à ce niveau de l'assemblage d'une page web...
    mais ce serait idiot, on peut cependant créer un filtre pour htmlspecialChars
*/
class TemplateRunner
{
    const RUN_MODE = 'RUN_MODE';
    const SEARCH_MODE = 'SEARCH_MODE';
    private $workmode;
    private $hooksFound;

    /*
        url du fichier tpl
    */
    private $_current_path;

    /*
        contenu du fichier tpl
        qui va être compilé
    */
    private $_tpl;

    /*
        Tableau global et local
            Le tableau global est disponible tout le temps et est prioritaire face au tableau locale.
            Le tableau local est disponible uniquement pour la génération du template en cours.
        liste de paire clé/valeur:
        array(key => data);
    */
    private $_filtersGlobalVars;
    private $_globalVars;
    private $_templateVars;

    /*
        Listes d'élements:
        $_bind_data_to_tpl[REGEX] => array(
            'data' => 'valeur compilé en fonction de la globalVars/templateVars',
            'filters' => array(
                'nom du filtre' => array(
                    0 => 'argument 1 pour le filtre'
                )
            )
        )
    */
    private $_bind_data_to_tpl;

    /*
        Message d'erreur en cas de template impossible à compiler/interpreter
    */
    private $_errors;
    private $_hasError;

    /*
    Regex Entière:
    REGEX = {{\s*([A-Za-z]+[A-Za-z0-9]*([.][A-Za-z]+[A-Za-z0-9]*)*|'[^:|]*?')\s*([|]\s*[A-Za-z]+[A-Za-z0-9]*\s*(:\s*([A-Za-z]+[A-Za-z0-9]*([.][A-Za-z]+[A-Za-z0-9]*)*|'[^:|]*?')\s*)*)*\s*}}
    */

    //NOEUD
    const REGEX = "{{\s*".self::EXPRESSION_REGEX."\s*".self::FILTERS_REGEX."\s*}}";

    public function __construct()
    {
        $this->_globalVars = array();
        $this->_filtersGlobalVars = array();

        foreach (FilterProvider::getFilters() as $filter) {
            $this->_filtersGlobalVars[$filter->getName()] = $filter->getFilterGlobalVars();
        }
    }

    private function compileTplRegex($regex)
    {
        $regex = trim(substr($regex, 2, strlen($regex) - 4));
        $explodeRegex = explode('|', $regex);

        return array('data' => $this->getTplExpression($explodeRegex[0]), 'filters' => $this->getTplFilters($regex));
    }

    public function addGlobals($globals)
    {
        foreach ($globals as $key => $value) {
            $this->addGlobalVar($key, $value);
        }
    }
    private function addGlobalVar($key, $value)
    {
        if (!isset($this->_globalVars[$key])) {
            $this->_globalVars[$key] = $value;
        }
    }

    private function keyExist($key)
    {
        if (!array_key_exists($key, $this->_globalVars)) {
            return array_key_exists($key, $this->_templateVars);
        }

        return true;
    }

    private function getValue($key, $filterGlobalVars)
    {
        if (array_key_exists($key, $filterGlobalVars)) {
            return $filterGlobalVars[$key];
        }

        if ($this->workmode == self::SEARCH_MODE) {
            $this->addToHooks($key);

            return '000';
        }

        if (!$this->keyExist($key)) {
            $this->addError('Template: Unknown variable: '."'".$key."'");

            return 'NULL';
        }

        if (!array_key_exists($key, $this->_globalVars)) {
            return $this->_templateVars[$key];
        }

        return $this->_globalVars[$key];
    }

    //FEUILLE:
    const STRING_REGEX = "'[^:|]*?'";

    private function isTplString($string)
    {
        $string = trim($string);

        return $string[0] == "'";
    }

    private function eatTplString($string)
    {
        $string = trim($string);

        return substr($string, 1, strlen($string) - 2);
    }

    //FEUILLE:
    const WORD_REGEX = '(_|[A-Za-z])+[A-Za-z0-9]*';

    private function eatTplWord($word)
    {
        $word = trim($word);

        return $word;
    }

    //NOEUD
    const VAR_REGEX = self::WORD_REGEX.'([.]'.self::WORD_REGEX.')*';

    private function getTplVar($var, $filterGlobalVars)
    {
        $array_var = explode('.', $var);

        $variableContainer = $this->getValue($this->eatTplWord($array_var[0]), $filterGlobalVars);

        if ($this->workmode == self::SEARCH_MODE) {
            return '000';
        }

        if (count($array_var) == 1) {
            return $variableContainer;
        }

        for ($i = 1; $i < count($array_var); ++$i) {
            $variableContainer = $this->call($variableContainer, $this->eatTplWord($array_var[$i]));
        }

        return $variableContainer;
    }

    //NOEUD
    const EXPRESSION_REGEX = '('.self::VAR_REGEX.'|'.self::STRING_REGEX.')';

    private function getTplExpression($expression, $filterGlobalVars = array())
    {
        $expression = trim($expression);
        if ($this->isTplString($expression)) {
            return $this->eatTplString($expression);
        }

        return $this->getTplVar($expression, $filterGlobalVars);
    }

    //NOEUD
    const ARGS_REGEX = "([:]\s*".self::EXPRESSION_REGEX."\s*)*";

    private function getTplArgs($args)
    {
        $return_args = array();

        $array_args = explode(':', $args);

        for ($i = 1; $i < count($array_args); ++$i) {
            $return_args[] = $this->getTplExpression($array_args[$i], $this->_filtersGlobalVars[trim($array_args[0])]);
        }

        return $return_args;
    }

    //NOEUD
    const FILTERS_REGEX = "([|]\s*".self::WORD_REGEX."\s*".self::ARGS_REGEX.')*';

    private function getTplFilters($filters)
    {
        $return_filters = array();

        $array_filters = explode('|', $filters);

        for ($i = 1; $i < count($array_filters); ++$i) {
            $filter_args = explode(':', $array_filters[$i]);

            $return_filters[trim($filter_args[0])] = $this->getTplArgs($array_filters[$i]);
        }

        return $return_filters;
    }

    /*
        Alias pour la méthode run
        Raison: mauvaise conception de départ.
    */
    public function show($path)
    {
        return $this->run($path);
    }

    private function addToHooks($hook)
    {
        if (!in_array($hook, $this->hooksFound)) {
            $this->hooksFound[] = $hook;
        }
    }

    //Raccourcis pour la recherche des variables nécéssaire à ce template.
    public function searchVars($path)
    {
        return $this->run($path, array(), self::SEARCH_MODE);
    }

    /**
     Cela ne recherche que les variables de templates à définir manuellement (exclus les variables présentes dans le scope global et dans le scope des filtres).
     */
    public function run($path, array $templateVars = array(), $workmode = self::RUN_MODE)
    {
        $this->workmode = $workmode;
        $this->hooksFound = array();

        $this->_current_path = $path;
        $this->_hasError = false;
        $this->_errors = '<pre>';

        try {
            $this->_tpl = file_get_contents(Config::TPL_DIR.$path);
            if ($this->_tpl == false) {
                throw new \Exception('failed to open stream: No such file or directory');
            }
        } catch (\Exception $e) {
            $this->addError($e->getMessage());
        }

        $this->_templateVars = $templateVars;
        $this->interpretRegex();
        if ($this->workmode == self::SEARCH_MODE) {
            return $this->hooksFound;
        }

        $this->runFilters();
        $this->compile();

        $this->_errors .= '</pre>';

        if ($this->_hasError) {
            return $this->_errors;
        }

        return $this->_tpl;
    }

    private function interpretRegex()
    {
        $this->_bind_data_to_tpl = array();

        $matches = null;
        preg_match_all('/'.self::REGEX.'/', $this->_tpl, $matches);

        foreach ($matches[0] as $key => $value) {
            if (!array_key_exists($value, $this->_bind_data_to_tpl)) {
                $this->_bind_data_to_tpl[$value] = $this->compileTplRegex($value);
            }
        }
        //exit;
    }

    private function runFilters()
    {
        foreach ($this->_bind_data_to_tpl as $regex => $interpretTpl) {
            foreach ($interpretTpl['filters'] as $filter => $args) {
                if (FilterProvider::getFilter($filter) == null) {
                    $this->addError('FilterProvider: Unknown filter: '.$filter);
                } else {
                    $this->_bind_data_to_tpl[$regex]['data'] = FilterProvider::getFilter($filter)->filter($this->_bind_data_to_tpl[$regex]['data'], $args, $this->_globalVars);
                }
            }
        }
    }

    private function compile()
    {
        foreach ($this->_bind_data_to_tpl as $key => $value) {
            if (is_object($value['data']) || is_array($value['data'])) {
                $value['data'] = '[WARNING: '.$this->_current_path.'] Object or Array received for '.$key.': <pre>'.var_export($value['data'], true).'</pre>';
            }

            $this->_tpl = str_replace($key,  $value['data'], $this->_tpl);
        }
    }

    private function call($variable, $variableCall)
    {
        if (is_object($variable)) {
            if (method_exists($variable, $variableCall)) {
                $callable = array($variable, $variableCall);

                return call_user_func($callable);
            }

            if (!property_exists($variable, $variableCall)) {
                $this->addError('CompileTemplate: property: '.$variableCall.' not found inside: '.get_class($variable));

                return 'NULL';
            }

            return $variable->$variableCall;
        }
        if (is_array($variable)) {
            if (!array_key_exists($variableCall, $variable)) {
                $this->addError('CompileTemplate: key: '.$variableCall.' not found inside array');

                return 'NULL';
            }

            return $variable[$variableCall];
        }

        $this->addError("CompileTemplate: variable isn't either array or object : ".$variable.' unable to call: '.$variableCall);
        var_export($variable);
    }

    private function addError($error)
    {
        $this->_hasError = true;

        $this->_errors .= '[ERROR: '.$this->_current_path.']'.$error.";\n";
    }
}
