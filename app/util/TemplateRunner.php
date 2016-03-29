<?php

namespace app\util;

use app\config\Config;
use app\util\Filter;
use app\util\FilterProvider;

//preg_match_all : {{\s*[A-Za-z]*\s*}}
//preg_match_all : {{\s*[A-Za-z0-9]+\s*(:\s*[A-Za-z0-9]+\s*)*}} => filter functionality is working

/*
	faiblesse de cette façon de proceder: 
		impossibilité d'utilisé la méthode htmlspecialchars à ce niveau de l'assemblage d'une page web...
*/
class TemplateRunner {
	//V1: Une variable de ce moteur de template doit etre comme celle la: {{ mavar[.somethingElse] [| filter [: arg0Nfois]] }}
	
	//const REGEX = "{{\s*[A-Za-z]+[A-Za-z0-9]*([.][A-Za-z]+[A-Za-z0-9]*)*\s*([|]\s*[A-Za-z]+[A-Za-z0-9]*\s*(:\s*([A-Za-z]+[A-Za-z0-9]*|'.*')\s*)*)*}}";
	
	/*
		Un moteur de template avec des expressions en Antlr aurait été vraiment plus facile à créer
		V2: ajout des chaines de caractères en tant que variable: {{ (mavar[.somethingElse]|'maChaine de caractère') [| filter [: arg0Nfois]] }}
		Vérifier si une chaine de caractère peut contenir un simple quote
	*/
	//const REGEX = "{{\s*([A-Za-z]+[A-Za-z0-9]*([.][A-Za-z]+[A-Za-z0-9]*)*|'.*')\s*([|]\s*[A-Za-z]+[A-Za-z0-9]*\s*(:\s*([A-Za-z]+[A-Za-z0-9]*([.][A-Za-z]+[A-Za-z0-9]*)*|'.*')\s*)*)*}}";
	

	/*
		REGEX = {{\s*([A-Za-z]+[A-Za-z0-9]*([.][A-Za-z]+[A-Za-z0-9]*)*|'.*')\s*([|]\s*[A-Za-z]+[A-Za-z0-9]*\s*(:\s*([A-Za-z]+[A-Za-z0-9]*([.][A-Za-z]+[A-Za-z0-9]*)*|'.*')\s*)*)*\s*}}
	*/

	//FEUILLES:
	const STRING_REGEX = "'[^:|]*'";
	const WORD_REGEX = "[A-Za-z]+[A-Za-z0-9]*";

	//NOEUDS
	const VAR_REGEX = "(" . self::WORD_REGEX . "[.]" . self::WORD_REGEX . ")*";

	const EXPRESSION_REGEX = "(" . self::VAR_REGEX . "|" . self::STRING_REGEX . ")";

	const ARGS_REGEX = "(:\s*" . self::EXPRESSION_REGEX . "\s*)*";

	const FILTER_REGEX = "([|]\s*" . self::WORD_REGEX . "\s*" . self::ARGS_REGEX . ")*";

	const REGEX = "{{\s*" . self::EXPRESSION_REGEX . "\s*" . self::FILTER_REGEX . "\s*}}";

	/*
		Il serait intéressant d'autorisé la concaténation de filtre
		Toutes les variables possibles:
		([A-Za-z]+[A-Za-z0-9]*([.][A-Za-z]+[A-Za-z0-9]*)*|'.*')

		Une variables commence forcément par une lettre,
		Une chaines de caractère est forcément placée entre : "'"
			cela comprend:
				[Attention, une chaine de caractère ne doit pas contenir de simple quote]
				string: 'chaine de caractère'
				variable: maVar
				array: monTableau.maVariable
				objet: monObjet.maMethodeOuMaVariable

		créer une fonction qui prend en paramètre une variable et qui retourne les données qui correspondent:
			la valeur de la variable, de la chaine de caractère, ou de la méthode/variable d'objet.
	*/
	private $_tpl;
	private $_current_path;
	//array(key => data);
	private $_mapping_data;
	//array("templateKey" => array("key" => key, "filter" => FilterObject, "args" => args, "data" => data))
	private $_bind_data_to_tpl;
	private $_errors;
	private $_hasError;
	private $_globalVars;

	public function __construct() {
		$this->_globalVars = array();
	}

	public function addGlobalVar($key, $value) {
		$this->_globalVars[$key] = $value;
	}

	public function keyExist($key) {
		if(!array_key_exists($key, $this->_globalVars))
			return array_key_exists($key, $this->_mapping_data);
		return true;
	}

	public function getValue($key) {
		if(!array_key_exists($key, $this->_globalVars))
			return $this->_mapping_data[$key];
		return $this->_globalVars[$key];
	}

	public function show($path) {
		$this->_tpl = file_get_contents (Config::TPL_DIR . $path);
		return $this->_tpl;
	}

	public function run($path, array $mapping_data) {
		$this->_current_path = $path;
		$this->_hasError = false;
		$this->_errors = "<pre>";

		try{
 			$this->_tpl = file_get_contents (Config::TPL_DIR . $path);
 			if($this->_tpl == false)
 				throw new \Exception("failed to open stream: No such file or directory");
 				
		}
		catch (\Exception $e) {
			$this->addError($e->getMessage());
		}	

		$this->_mapping_data = $mapping_data;
		$this->analyzeTemplate();
		/*echo "<pre>";
		var_export($this->_bind_data_to_tpl);
		echo "</pre>";*/
		$this->analyzeKeys();
		$this->linkingFilters();
		$this->compile();

		$this->_errors .= "</pre>";

		if($this->_hasError)
			return $this->_errors;

		return $this->_tpl;
	}

	private function analyzeTemplate() {
		$this->_bind_data_to_tpl = array();
	
		$matches = null;
		preg_match_all ("/" . self::REGEX . "/", $this->_tpl, $matches);
		foreach ($matches[0] as $key => $value) {
			if(!array_key_exists($value, $this->_bind_data_to_tpl)){
				$this->_bind_data_to_tpl[$value] = array("key" => trim(substr($value, 2, count($value) - 3)), "filter" => null, "args" => null, "data" => null);
			}
		}
	}

	/**
		Analyse les expressions reconnu par ce moteur de template
	*/
	private function analyzeKeys() {
		foreach ($this->_bind_data_to_tpl as $key => $value) {
			if(strrchr($value["key"], "|") != false) {
				$filterFilter = explode("|", $value["key"]);
				$this->_bind_data_to_tpl[$key]["key"] =  trim($filterFilter[0]);


				$filterArgs = explode(":", $filterFilter[1]);
				$this->_bind_data_to_tpl[$key]["filter"] = trim($filterArgs[0]);
				if(count($filterArgs) > 1) {
					$filterArgs = array_slice($filterArgs, 1, count($filterArgs));

					foreach ($filterArgs as $key2 => $value2) {
						$filterArgs[$key2] = trim($value2);
						//if filter arguments is a String
						if($filterArgs[$key2][0] == "'"){
							$filterArgs[$key2] = substr($filterArgs[$key2], 1, count($filterArgs[$key2]) - 2);
						}
						else {//if filter arguments is a variable
							if(!$this->keyExist($filterArgs[$key2])) {
								$this->addError('Filter: Unknown $var: ' . "'" . $filterArgs[$key2] . "'");
							}
							else
								$filterArgs[$key2] = $this->getValue($filterArgs[$key2]);
						}
					}

					$this->_bind_data_to_tpl[$key]["args"] = $filterArgs;
				}
			}
		}
	}

	private function linkingFilters() {
		foreach ($this->_bind_data_to_tpl as $key => $value) {
			if($value["filter"] != null) {
				$filterObject = FilterProvider::getFilter($value["filter"]);
				if($filterObject == null) {
					$this->addError("FilterProvider: Unknown Filter '" . $value["filter"] . "'");
				}
				$this->_bind_data_to_tpl[$key]["filter"] = $filterObject;
			}
		}
	}

	//compile value requested by template according to mapping data 
	private function compile() {

		foreach ($this->_bind_data_to_tpl as $key => $value) {
			//trueKey est la clé qui doit être trouvé dans mappingData
			$trueKey = $value['key'];
			$elements = null;
			$isString = false;
			if($trueKey[0] == "'") {
				$data = substr($trueKey, 1, strlen($trueKey) -2);
				$isString = true;
			}

			if(!$isString && strrchr($value["key"], ".") != false ) {
				$elements = explode(".", $value["key"]);
				$trueKey = $elements[0];
				$elements = array_slice($elements, 1, count($elements));
			}

			if(!$isString && !$this->keyExist($trueKey)) {
				$this->addError("CompileTemplate: Unknown data '" . $trueKey . "'");
				continue;
			}

			if(!$isString) {
				if($elements == null)
					$data = $this->getValue($value["key"]);
				else {
					$currentVariable = $this->getValue($trueKey);
					foreach ($elements as $key2 => $value2) {
						$currentVariable = $this->call($currentVariable, $value2);
					}
					$data = $currentVariable;
				}
			}

			if($value["filter"] != null) {
				$data = $value["filter"]->filter($data, $value["args"]);
			}

			$this->_bind_data_to_tpl["data"] = $data;

			if(is_object($data) || is_array($data)) {
				$data = "[WARNING: " . $this->_current_path . "]Object or Array received for " . $key . ": <pre>" . var_export($data, true) . "</pre>";
			}
			
			$this->_tpl = str_replace($key,  $data, $this->_tpl);
		}
	}

	private function call($variable, $variableCall) {
		if(is_object($variable)) {
			//TODO: façon vraiment sale d'appeler une méthode ou une variable
			if(method_exists($variable, $variableCall)){
				$callable = array($variable, $variableCall);
				return call_user_func($callable);
			}
			return $variable->$variableCall;
		}

		if(is_array($variable)) {
			return $variable[$variableCall];
		}

		$this->addError("CompileTemplate: elements isn't either array or object : " . $variable);
		var_export($variable);
	}

	private function addError($error) {
		$this->_hasError = true;

		$this->_errors .= "[ERROR: " . $this->_current_path . "]" . $error . ";\n";
	}
}