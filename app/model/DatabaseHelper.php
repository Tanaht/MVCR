<?php

namespace app\model;

use app\config\Config;

class DatabaseHelper {
	private static $_bdd;
	private $_pdo;
	private $_statement;
	private $_sql;

	private function __construct(){
		$this->_sql = "";
		try{
			$this->_pdo = new \PDO(
				'mysql:host=' . Config::DB_HOST 
				. ';dbname=' . Config::DB_NAME . ";charset=utf8",
    			Config::DB_USER,
    			Config::DB_PWD,
    			array(\PDO::ATTR_ERRMODE=>\PDO::ERRMODE_EXCEPTION)
    		);
		}
		catch(\PDOException $e){
			Render::convert(new Parameters(), $e->getMessage());
			exit;
		}
	}

	public static function getBdd() {
		if(self::$_bdd == null)
			self::$_bdd = new DatabaseHelper();
		
		return self::$_bdd;
	}

	public function getId($name = null) {
		return array("id" => $this->_pdo->lastInsertId($name));
	}

	private function removeIndexKeys($row){
		foreach ($row as $key => $value) {
			if(is_int($key)){
				unset($row[$key]);
				continue;
			}
		}
		return $row;
	}

	private function toArray(){
		$count = 0;
		$table = array();
		
		if(!$this->_statement)
			return $table;
		
		foreach  ($this->_statement as $row) {
	        $table[$count++] = $this->removeIndexKeys($row);
	    }
	   
	    return $table;
	}

	/**
		call a method for each row of datas.
		the method call must possess a reference of the row parameter like this:
			callable(&$currentRow);
		concrete exemple: $bdd->forEachRow(array("restserver\controller\Utilisateurs", "getEtapesFromTrajet"));
	*/
	public function forEachRow(Callable $callable) {
		//Detach this request from a request inside callable
		$statement = $this->_statement;
		$count = 0;
		$table = array();
		$statement = $this->_pdo->query($this->_sql);
		
		foreach  ($statement as $row) {
	        $row = $this->removeIndexKeys($row);
	        call_user_func_array($callable, array(&$row));
	    	$table[$count++] = $row;
	    }
		
		return $table;
	}

	public function query($query) {
		$this->_sql = "";
		$this->_sql = $query;
	}

	public function execute($requestType = null){
		try{
			$this->_statement = $this->_pdo->query($this->_sql);
		}
		catch(\PDOException $e){
			return array("sqlQuery" => $this->_sql , "mysqlError" => $e->getMessage());
		}

		if($requestType == null)
			return $this->toArray();
		else{
			return array("request" => "success");
		}
	}
}