<?php

namespace Src;
use PDO;

class Model extends Database
{
    protected $table;
    protected $dbh;

    public  function __construct()
    {
        parent::__construct();
        $conn = $this->getInstance();
    }
    public function all($table)
    {
        // echo "SELECT * FROM `" . $table ."`";
        $stmt = $this->conn->query("SELECT * FROM `" . $table . "`");
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
       
        return $result;
    }

}