<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 12/8/2018
 * Time: 10:04 AM
 */
error_reporting(E_ALL);
ini_set('display_errors', 'On');

class DbInterface
{
    var $conn;

    function __construct()
    {

        $servername = "localhost";
        $username = "root";
        $password = "";
        $database = "game_crawler";

        $this->conn = new mysqli($servername, $username, $password, $database);

        // Check connection
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    function GetConnectionObj()
    {
        return $this->conn;
    }

    function getTable($sql)
    {
        $result = $this->conn->query($sql);
        return $result;
    }
    
   
    function ExecuteMultipleQuery($sql)
    {

        $result = mysqli_multi_query($this->conn, $sql);
        return $result;
    }
   


}



