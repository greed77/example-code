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

    public function insert($tablename, $params = []) {
        if (trim($tablename) !== "" && count($params) > 0) {
            foreach ($params as $field => $value) {
                $fields[] = $field;
                $values[$field] = $value;
            }

            $sql = "INSERT INTO $tablename (".implode(", ", $fields).") VALUES (" . ":" . implode(", :", $fields) . ")";
            $statement = $this->conn->prepare($sql);
            if (!$statement) {
                echo "\nPDO::errorInfo():\n";
                print_r($this->conn->errorInfo());
            }
            $statement->execute($values);

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