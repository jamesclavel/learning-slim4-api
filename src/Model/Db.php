<?php namespace App\Model;

use \PDO;

class DB
{
    private $host;
    private $user;
    private $pass;
    private $dbname;

    public function __construct()
    {
        $this->host   = $_ENV['DB_HOST'];
        $this->user   = $_ENV['DB_USER'];
        $this->pass   = $_ENV['DB_PASS'];
        $this->dbname = $_ENV['DB_NAME'];
    }

    public function connect()
    {
        $conn_str = "mysql:host=$this->host;dbname=$this->dbname";
        $conn = new PDO($conn_str, $this->user, $this->pass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        return $conn;
    }
}
