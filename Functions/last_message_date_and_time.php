<?php

class lastMessageTime
{

    private static $SERVER_NAME = "localhost";
    private static $USER_NAME = "root";
    private static $PASSWORD = "";
    private static $DATABASE_NAME = "automation";

    private static $MESSAGE = "message";
    private $conn = null;

    public function __construct(){
        try {

            $this->conn = new PDO("mysql:host=" . self::$SERVER_NAME . ";dbname=" . self::$DATABASE_NAME . ";", self::$USER_NAME, self::$PASSWORD);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->query("set names utf8");
        } catch (Exception $ex) {
            die($ex->getMessage());
        }
    }

    public function last_message_time() {
        try { 

            $stmt = " SELECT rec_mess_date, rec_mess_time FROM " 
            . self::$MESSAGE . " ORDER BY id DESC LIMIT 1 ";
            
            $showMessageTime = $this->conn->prepare($stmt);
            $showMessageTime->execute();
                        
            return $showMessageTime;

        } catch(Exception $ex) {
            die($ex->getMessage());
        }
    }

}


?>