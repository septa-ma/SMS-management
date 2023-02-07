<?php

class CheckUserAndSaveMessage {

    private static $SERVER_NAME = "localhost";
    private static $USER_NAME = "root";
    private static $PASSWORD = "";
    private static $DATABASE_NAME = "automation";

    private static $USER = "user";
    private static $ADMIN_RECORDS = 'admin_records';
    private static $MESSAGE = "message";
    private static $ANSWER = "answer";

    private $conn = null;

    public $first_name;
    public $last_name;
    public $phone_number;
    public $message;
    public $date;
    public $time;

    public function __construct(){
        try {

            $this->conn = new PDO("mysql:host=" . self::$SERVER_NAME . ";dbname=" . self::$DATABASE_NAME . ";", self::$USER_NAME, self::$PASSWORD);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->query("set names utf8");
        } catch (Exception $ex) {
            die($ex->getMessage());
        }
    }

    public function check_user_and_save_message() {
        try {
            $this->conn->beginTransaction();

            // check if the user that is registered or not
            $stmt1 = " SELECT id, phone_number FROM " . self::$USER . " WHERE phone_number = :phone_number LIMIT 1 ";
            $checkNewUser = $this->conn->prepare($stmt1);

            $phoneNumber = $this->phone_number;
            $checkNewUser->bindParam(":phone_number", $phoneNumber);

            $checkNewUser->execute();
            $checkNewUser->setFetchMode(PDO::FETCH_ASSOC);
            $row0 = $checkNewUser->fetchAll();

            if(count($row0) == 0) {
                // insert new user if not exist
                $stmt2 = " INSERT INTO " . self::$USER . " ( first_name, last_name, reg_date, reg_time, phone_number, password, delete_key ) 
				VALUES ( :first_name, :last_name, :reg_date, :reg_time, :phone_number, :password, :delete_key ) ";

                $insertingNewUser = $this->conn->prepare($stmt2);

                $first_name = htmlspecialchars(strip_tags($this->first_name));
                $last_name = htmlspecialchars(strip_tags($this->last_name));
                $dNow = date("Y-m-d", strtotime("now")); // htmlspecialchars(strip_tags($this->date));
                $tNow = date("H:i:s", strtotime("now")); // htmlspecialchars(strip_tags($this->time));
                $phone_number = $this->phone_number;
                $password = $last_name.'@6811';
                $delete_key = 10; // 10 not deleted, 11 is deleted
                
                $insertingNewUser->bindParam(":first_name", $first_name);
                $insertingNewUser->bindParam(":last_name", $last_name);
                $insertingNewUser->bindParam(":reg_date", $dNow);
                $insertingNewUser->bindParam(":reg_time", $tNow);
                $insertingNewUser->bindParam(":phone_number", $phone_number);
                $insertingNewUser->bindParam(":password", $password);
                $insertingNewUser->bindParam(":delete_key", $delete_key);
                
                $insertingNewUser->execute();
                $SenderUserId = $this->conn->lastInsertId();
                echo $SenderUserId;

            // get the sender user id
            } elseif (count($row0) == 1) {
                $SenderUserId = $row0[0]['id'];
                $phoneNum = $row0[0]['phone_number'];
            }

            // saveing new message
            $stmt3 = " INSERT INTO " . self::$MESSAGE . " ( user_id_sender, message, rec_mess_date, rec_mess_time, delete_key ) 
            VALUES ( :user_id_sender, :message, :rec_mess_date, :rec_mess_time,  :delete_key ) ";

            $insertingMessage = $this->conn->prepare($stmt3);
            $mesg = htmlspecialchars(strip_tags($this->message));
            $mdNow = /*date("Y-m-d", strtotime("now"));*/ htmlspecialchars(strip_tags($this->date));
            $mtNow = /*date("H:i:s", strtotime("now"));*/ htmlspecialchars(strip_tags($this->time));
            $deleteKey = 10;

            $insertingMessage->bindParam(":user_id_sender", $SenderUserId);
            $insertingMessage->bindParam(":message", $mesg);
            $insertingMessage->bindParam(":rec_mess_date", $mdNow);
            $insertingMessage->bindParam(":rec_mess_time", $mtNow);
            $insertingMessage->bindParam(":delete_key", $deleteKey);

            $insertingMessage->execute();
            $mesgId = $this->conn->lastInsertId();
            // echo $mesgId;

/**************** check if the user had someone for answering ****************/

            // get the message id 
            $stmt4 = " SELECT id FROM " . self::$MESSAGE 
            . " ORDER BY rec_mess_date ASC LIMIT 1 ";

            $getMesgId = $this->conn->prepare($stmt4);

            $getMesgId->execute();
            $getMesgId->setFetchMode(PDO::FETCH_ASSOC);
            $row1 = $getMesgId->fetchAll();

            if(count($row1) == 1) {
                $messageId = $row1[0]['id'];
                // echo $messageId;

                // get the selected user
                $stmt5 = " SELECT selected_user_id FROM " . self::$ADMIN_RECORDS 
                . " WHERE message_id = :message_id LIMIT 1";

                $getId = $this->conn->prepare($stmt5);
                // $messageId = 2;
                $getId->bindParam(":message_id", $messageId);
                $getId->execute();
                $getId->setFetchMode(PDO::FETCH_ASSOC);
                $row2 = $getId->fetchAll();
                // print_r($row2);

                if(count($row2) == 1) {
                    $userId = $row2[0]['selected_user_id'];
                    // echo $userId;
                    // echo 'hgjhgjhgjhg';

                    // add user and chat that should answer if someone is selected for answering!
                    $stmt6 = " INSERT INTO " . self::$ANSWER . " ( message_id, user_id_receiver, status ) 
                    VALUES ( :message_id, :user_id_receiver, :status ) ";

                    $insertingAnswer = $this->conn->prepare($stmt6);
                    $status = 20;

                    $insertingAnswer->bindParam(":message_id", $mesgId);
                    $insertingAnswer->bindParam(":user_id_receiver", $userId);
                    $insertingAnswer->bindParam(":status", $status);

                    $insertingAnswer->execute();
                } else {
                    echo 'message does not have user';
                }
            } else {
                echo 'not found';
            }
            $this->conn->commit();
            // return 8000;

        } catch (Exception $ex) {
            $this->conn->rollBack();
            die($ex->getMessage());
        }
    }

    public function sanitize_purpose_phone_input($input ) {
        $input = preg_replace( "/[^0-9]/", "", $input );
        if ( strlen( $input ) == 12 ) {
            if ( is_numeric( $input ) ) {
                return intval( $input );
            }
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