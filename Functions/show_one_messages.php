<?php

class ShowOneMessage
{

    private static $SERVER_NAME = "localhost";
    private static $USER_NAME = "root";
    private static $PASSWORD = "";
    private static $DATABASE_NAME = "automation";

    private static $USER = "user";
    private static $MESSAGE = "message";
    private $conn = null;

    public function __construct(){
        try {

            $this->conn = new PDO("mysql:host=" . self::$SERVER_NAME . ";dbname=" . self::$DATABASE_NAME . ";", self::$USER_NAME, self::$PASSWORD);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch (Exception $ex) {
            die($ex->getMessage());
        }
    }

    public function show_one_message($id) {
        try { 

            $stmt = " SELECT M.message, M.rec_mess_date, M.rec_mess_time, U.first_name, U.last_name, U.phone_number FROM " 
            . self::$MESSAGE . " AS M INNER JOIN " . self::$USER . " AS U "
            . " WHERE M.user_id_sender = U.id AND M.id = :msgId ";

            $showAllMessage = $this->conn->prepare($stmt);
            $showAllMessage->bindParam(":msgId", $id);
            $showAllMessage->execute();
            // print_r($rows);
            return $showAllMessage;

        } catch(Exception $ex) {
            die($ex->getMessage());
        }
    }

}


?>