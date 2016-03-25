<?php

namespace app\util;

use app\config\Config;
use app\util\Filter;
use app\util\FilterProvider;

//preg_match_all : {{\s*[A-Za-z]*\s*}}
//preg_match_all : {{\s*[A-Za-z0-9]+\s*(:\s*[A-Za-z0-9]+\s*)*}} => filter functionality is working

class TemplateRunner {
	//Une variable de ce moteur de template doit etre comme celle la: {{ mavar[.somethingElse] [| filter [: arg0Nfois]] }}
	const REGEX = "{{\s*[A-Za-z]+[A-Za-z0-9]*([.][A-Za-z]+[A-Za-z0-9]*)*\s*([|]\s*[A-Za-z]+[A-Za-z0-9]*\s*(:\s*([A-Za-z]+[A-Za-z0-9]*|'.*')\s*)*)*}}";
	
	private $_tpl;

	//array(key => data);
	private $_mapping_data;
	//array("templateKey" => array("key" => key, "filter" => FilterObject, "args" => args, "data" => data))
	private $_bind_data_to_tpl;
	private $_errors;
	private $_hasError;

	public function show($path) {
		$this->_tpl = file_get_contents (Config::TPL_DIR . $path);
		return $this->_tpl;
	}

	public function run($path, array $mapping_data) {
		$this->_hasError = false;
		$this->_errors = "<pre>";

 		$this->_tpl = file_get_contents (Config::TPL_DIR . $path);
		
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
							if(!array_key_exists($filterArgs[$key2], $this->_mapping_data)) {
								$this->addError('Filter: Unknown $var: ' . "'" . $filterArgs[$key2] . "'");
							}
							$filterArgs[$key2] = $this->_mapping_data[$filterArgs[$key2]];
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
			if(strrchr($value["key"], ".") != false) {
				$elements = explode(".", $value["key"]);
				$trueKey = $elements[0];
				$elements = array_slice($elements, 1, count($elements));
			}

			if(!array_key_exists($trueKey, $this->_mapping_data)) {
				$this->addError("CompileTemplate: Unknown data '" . $trueKey . "'");
				continue;
			}

			if($elements == null)
				$data = $this->_mapping_data[$value["key"]];
			else {
				$currentVariable = $this->_mapping_data[$trueKey];
				foreach ($elements as $key2 => $value2) {
					$currentVariable = $this->call($currentVariable, $value2);
				}
				$data = $currentVariable;
			}

			if($value["filter"] != null) {
				$data = $value["filter"]->filter($data, $value["args"]);
			}

			$this->_bind_data_to_tpl["data"] = $data;


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

		$this->_errors .= "[ERROR]" . $error . ";\n";
	}
}