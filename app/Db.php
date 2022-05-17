<?php
namespace app;
class Db
{
    private $conn;

    public function __construct()
    {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "bookapi";

        $this->conn = mysqli_connect($servername, $username, $password, $dbname);


    }

    public function getConnection()
    {

        return $this->conn;
    }
}


?>