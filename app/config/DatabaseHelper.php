<?php

namespace app\config;

class DatabaseHelper
{
    private static $_bdd;
    private $_pdo;
    private $_statement;
    private $_sql;

    private function __construct()
    {
        $this->_sql = '';
        $this->_pdo = new \PDO(
                'mysql:host='.Config::DB_HOST
                .';dbname='.Config::DB_NAME.';charset=utf8',
                Config::DB_USER,
                Config::DB_PWD,
                array(\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION)
            );
    }

    public static function getBdd()
    {
        if (self::$_bdd == null) {
            self::$_bdd = new self();
        }

        return self::$_bdd;
    }

    public function getId($name = null)
    {
        return array('id' => $this->_pdo->lastInsertId($name));
    }

    private function removeIndexAndHtmlChars($row)
    {
        foreach ($row as $key => $value) {
            if (is_int($key)) {
                unset($row[$key]);
                continue;
            }
            $row[$key] = $value;
            //$row[$key] = htmlspecialchars($value);
        }

        return $row;
    }

    private function toArray()
    {
        $count = 0;
        $table = array();

        if (!$this->_statement) {
            return $table;
        }

        foreach ($this->_statement as $row) {
            $table[$count++] = $this->removeIndexAndHtmlChars($row);
        }

        return $table;
    }

    /**
     concrete exemple: $bdd->forEachRow(array("restserver\controller\Utilisateurs", "getEtapesFromTrajet"));
     */
    public function forEachRow(callable $callable)
    {
        //Detach this request from a request inside callable
        $statement = $this->_statement;
        $count = 0;
        $table = array();

        try {
            $statement = $this->_pdo->query($this->_sql);
        } catch (\PDOException $e) {
            echo '<pre>';
            var_export(array('sqlQuery' => $this->_sql, 'mysqlError' => $e->getMessage()));
            echo '</pre>';

            return;
        }

        foreach ($statement as $row) {
            $row = $this->removeIndexAndHtmlChars($row);
            call_user_func_array($callable, array(&$row));
            $table[$count++] = $row;
        }

        return $table;
    }

    public function query($query)
    {
        $this->_sql = '';
        $this->_sql = $query;
    }

    public function prepare($query)
    {
        $this->_statement = $this->_pdo->prepare($query);
    }

    public function bindParam($key, $value)
    {
        $this->_statement->bindParam($key, $value);
    }

    public function beginTransaction()
    {
        $this->_pdo->beginTransaction();
    }

    public function rollback()
    {
        $this->_pdo->rollback();
    }

    public function commit()
    {
        $this->_pdo->commit();
    }

    public function execute($selectRequest = true, $updateRequest = false)
    {
        if (!$selectRequest) {
            try {
                $this->_statement->execute();
            } catch (\PDOException $e) {
                echo '<pre>';
                var_export(array('statement' => $this->_statement, 'mysqlError' => $e->getMessage()));
                echo '</pre>';

                return false;
            }

            return true;
        }

        try {
            $this->_statement = $this->_pdo->query($this->_sql);
        } catch (\PDOException $e) {
            /*echo "<pre>";
            var_export(array("sqlQuery" => $this->_sql , "mysqlError" => $e->getMessage()));
            echo "</pre>";*/
            return false;
        }

        if ($updateRequest) {
            return true;
        }

        return $this->toArray();
    }
}
