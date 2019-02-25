<?php

class db
{

    private $server = "127.0.0.1";
    private $user = "db_user";
    private $dbname = "db_name";
    private $pass = "super_secure_password";

    private $conn;

    function __construct() {
        if ($this->conn == null) { $this->connect(); }
    }

    public function connect() {
        // Create connection
        $datasource = "mysql:host=" . $this->server . ";dbname=" . $this->dbname . ";";
        $this->conn = new PDO($datasource, $this->user, $this->pass);
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
                $fields[] = "`$field`";
                $values[] = "'".$value."'";
            }

            $fields = implode(", ", $fields);
            $values = implode(", ", $values);
            $sql = "INSERT INTO `contacts` ($fields) VALUES ($values)";

            return $this->conn->query($sql);
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