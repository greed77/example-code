<?php

require_once ("../db.php");

class table
{
    private $db;

    function __construct() {
        if ($this->db == null) { $this->db = new db(); }
    }

    public function insert($tablename, $params = []) {
        return $this->db->insert($tablename, $params);
    }

}