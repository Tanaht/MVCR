<?php

namespace app\model;
use app\config\Config;


class DatabaseHelper {
	private $_bdd;
	private $_pdo;

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
			echo "<pre>";
			echo $e->getMessage();
			echo "</pre>";
			exit;
		}
	}

	public static function getInstance() {
		if(self::$_bdd == null)
			self::$_bdd = new DatabaseHelper();
	}

	private function toArray($statement){
		$count = 0;
		$table = array();

		if(!$statement)
			return $table;

		foreach  ($statement as $row) {
	        $table[$count++] = $row;
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
		
		$count = 0;
		$table = array();
		
		$statement = $this->_pdo->query($this->_bdd->_sql);

		foreach  ($statement as $row) {
	        $row = $this->removeIndexKeys($row);
	        call_user_func_array($callable, array(&$row));
	    	$table[$count++] = $row;
	    }

		return $table;
	}

	public function execute($sqlQuery){
		$statement = null;
		try{
			$statement = $this->_pdo->query($sqlQuery);
		}
		catch(\PDOException $e){
			echo "<pre>";
			echo "SQL REQUEST: " . $sqlQuery . "<br/>";
			echo "SQL EXCEPTION: " . $e->getMessage();
			echo "</pre>";
			exit;
		}

		return $this->toArray($statement);
	}
}