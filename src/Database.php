<?php

namespace Src;

require_once ROOT_FOLDER . "/app/config/Config.php";

use PDO;
use PDOException;
use PDOStatement;
use Config;


class Database
{
    protected $db_name;
    protected $user;
    protected $pass;
    protected $host;
    protected $port;
    protected $conn = null;

    public function __construct()
    {
        $this->db_name = DB_NAME;
        $this->user = DB_USER;
        $this->pass = DB_PASS;
        $this->host = DB_HOST;
        $this->port = DB_PORT;
        $this->getInstance();
    }

    public function getInstance()
    {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname='. DB_NAME;
        var_dump($dsn, $this->user, $this->pass, $this->host);
        $this->conn = new PDO($dsn, $this->user, $this->pass); die('yes');

        if ($this->conn == null) {
            $this->conn = new PDO($dsn, $this->user, $this->pass);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
      
        return $this->conn;
    }

}