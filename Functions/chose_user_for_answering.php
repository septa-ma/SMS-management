<?php

class ChoseUser
{

    private static $SERVER_NAME = "localhost";
    private static $USER_NAME = "root";
    private static $PASSWORD = "";
    private static $DATABASE_NAME = "automation";

    private static $USER = "user";
    private static $MESSAGE = "message";
    private static $ANSWER = "answer";
    private static $ADMIN_RECORDS = "admin_records";

    private $conn = null;

    public $msgId;
    public $fullName;

    public function __construct(){
        try {

            $this->conn = new PDO("mysql:host=" . self::$SERVER_NAME . ";dbname=" . self::$DATABASE_NAME . ";", self::$USER_NAME, self::$PASSWORD);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->query("set names utf8");
        } catch (Exception $ex) {
            die($ex->getMessage());
        }
    }

    public function chose_user_for_answering(){
        try {

            $this->conn->beginTransaction();

            // get userId which choseing for answering the message.
            $stmt1 = " SELECT U.id AS userId, M.id AS mesgId  FROM " . self::$USER 
            . " AS U INNER JOIN " . self::$MESSAGE . " AS M "
            . " WHERE CONCAT(U.first_name, ' ', U.last_name) LIKE :fullName "
            . " AND M.id = :msgId LIMIT 1 ";

            $checkNewUser = $this->conn->prepare($stmt1);

            $fn = $this->fullName;
            $mId = $this->msgId;

            $checkNewUser->bindParam(":fullName", $fn);
            $checkNewUser->bindParam(":msgId", $mId);

            $checkNewUser->execute();
            $checkNewUser->setFetchMode(PDO::FETCH_ASSOC);
            $rowUser = $checkNewUser->fetch();
            // print_r($rowUser);
            if(count($rowUser) >= 1) {
                // $rows  = $rowUser;
                $userId = $rowUser['userId'];
                $mesgId = $rowUser['mesgId'];
                // echo $userId;
                // echo $mesgId;
            } else {
                return -1;
            }

            // save reord for admin
            $stmt2 = " INSERT INTO " . self::$ADMIN_RECORDS . " ( selected_user_id, message_id, status ) 
            VALUES ( :selected_user_id, :message_id, :status ) ";

            $savingRecords = $this->conn->prepare($stmt2);
            $status = 20;

            $savingRecords->bindParam(":selected_user_id", $userId);
            $savingRecords->bindParam(":message_id", $mesgId);
            $savingRecords->bindParam(":status", $status);

            $savingRecords->execute();

            // add user and chat that should be answered
            $stmt3 = " INSERT INTO " . self::$ANSWER . " ( message_id, user_id_receiver, status ) 
            VALUES ( :message_id, :user_id_receiver, :status ) ";

            $insertingAnswer = $this->conn->prepare($stmt3);
            $status = 20;

            $insertingAnswer->bindParam(":message_id", $mesgId);
            $insertingAnswer->bindParam(":user_id_receiver", $userId);
            $insertingAnswer->bindParam(":status", $status);

            $insertingAnswer->execute();

            $this->conn->commit();

        } catch (Exception $ex) {
            die($ex->getMessage());
        }
    }

    public function check_empty($data, $fields)
    {
        $msg = null;
        foreach ($fields as $value) {
            if (empty($data[$value])) {
                $msg .= "$value field empty <br />";
            }
        }
        return $msg;
    }

}

?>