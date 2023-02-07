<?php

class ShowAllUsers
{

    private static $SERVER_NAME = "localhost";
    private static $USER_NAME = "root";
    private static $PASSWORD = "";
    private static $DATABASE_NAME = "automation";

    private static $USER = "user";
    // private static $MESSAGE = "message";
    private $conn = null;

    public function __construct(){
        try {

            $this->conn = new PDO("mysql:host=" . self::$SERVER_NAME . ";dbname=" . self::$DATABASE_NAME . ";", self::$USER_NAME, self::$PASSWORD);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch (Exception $ex) {
            die($ex->getMessage());
        }
    }

    public function show_all_users() {
        try { 

            $stmt = " SELECT * FROM " . self::$USER 
            . " WHERE status = :status ";

            $status = 30; // 32 is manager 30 and 31 are admin and sender user.
            $showAllUsers = $this->conn->prepare($stmt);
            $showAllUsers->bindParam(":status", $status);
            $showAllUsers->execute();
            // print_r($rows);
            return $showAllUsers;

        } catch(Exception $ex) {
            die($ex->getMessage());
        }
    }

}


?>