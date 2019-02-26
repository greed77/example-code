<?php
require_once('config.php');

class db
{

    private $conn;

    function __construct() {
        if ($this->conn == null) { $this->connect(); }
    }

    public function connect() {
        // Create connection
        $datasource = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";";
        $this->conn = new PDO($datasource, DB_USER, DB_PASS);
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return true;
    }

    public function fetch($params = []) {
        $filters[] = "1=1";
        foreach ($params as $field => $value) {
            $filters[] = "`$field` = $value";
        }

        $filters = implode(' AND ', $filters);
        $sql = "SELECT * FROM `contacts` WHERE " . $filters;

        $results = $this->conn->query($sql);
        $records = $results->fetchAll();

        return $records;
    }

    public function insert($params = []) {
        if (count($params) > 0) {
            foreach ($params as $field => $value) {
                $fields[] = $field;
                $values[$field] = $value;
            }

            $sql = "INSERT INTO contacts (".implode(", ", $fields).") VALUES (" . ":" . implode(", :", $fields) . ")";
//            echo __LINE__ . ":" . $sql . "\n";
//            echo __LINE__ . ":" . print_r($values, true) . "\n";
            $statement = $this->conn->prepare($sql);
            if (!$statement) {
//                echo __LINE__ . "\n";
                echo "\nPDO::errorInfo():\n";
                print_r($this->conn->errorInfo());
            }
            $statement->execute($values);

//            echo __LINE__ . ":" . print_r($statement, true) . "\n";

            return $statement;
        }
        else {
            return false;
        }
    }

    public function update() {

    }

    public function delete($params = []) {
        if (count($params) > 0) {
            foreach ($params as $field => $value) {
                $filters[] = "`$field` = $value";
            }

            $filters = implode(' AND ', $filters);
            $sql = "DELETE FROM `contacts` WHERE " . $filters;

            return $this->conn->query($sql);
        }
        else {
            return false;
        }
    }
}