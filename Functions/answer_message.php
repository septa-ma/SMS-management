<?php

class AnswerMessage
{
    private static $SERVER_NAME = "localhost";
    private static $USER_NAME = "root";
    private static $PASSWORD = "";
    private static $DATABASE_NAME = "automation";

    private static $USER = "user";
    // private static $ADMIN_RECORDS = 'admin_records';
    private static $MESSAGE = "message";
    private static $ANSWER = "answer";
    
    private $conn = null;

    public $message;
    public $mesgId;

    public function __construct(){
        try {

            $this->conn = new PDO("mysql:host=" . self::$SERVER_NAME . ";dbname=" . self::$DATABASE_NAME . ";", self::$USER_NAME, self::$PASSWORD);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->query("set names utf8");
        } catch (Exception $ex) {
            die($ex->getMessage());
        }
    }

    public function answer_message(){
        try {

            //edit answer table based on its id
            $stmt1 = " UPDATE " . self::$ANSWER . " SET 
            answer_mess = :answer_mess, 
            answer_date = :answer_date,
            answer_time = :answer_time,
            status = :status 
            WHERE id = :id
            AND 
            delete_key = :delete_key ";
            $answerUser = $this->conn->prepare($stmt1);

            $messageId = $this->mesgId;
            $answer_mess = htmlspecialchars(strip_tags($this->message));
            $dNow = date("Y-m-d", strtotime("now"));
            $tNow = date("H:i:s", strtotime("now"));
            $status = 21;
            $deleteKey = 10;

            $answerUser->bindParam(":id", $messageId);
            $answerUser->bindParam(":answer_mess", $answer_mess);
            $answerUser->bindParam(":answer_date", $dNow);
            $answerUser->bindParam(":answer_time", $tNow);
            $answerUser->bindParam(":status", $status);
            $answerUser->bindParam(":delete_key", $deleteKey);

            $answerUser->execute();

        } catch (Exception $ex) {
            die($ex->getMessage());
        }
    }
    
    public function sms_message(){
        try {

            $stmt1 = " SELECT U.phone_number, A.answer_mess FROM " 
            . self::$MESSAGE . " AS M INNER JOIN " 
            . self::$USER . " AS U INNER JOIN " 
            . self::$ANSWER . " AS A "
            . " WHERE "
            . " M.user_id_sender = U.id "
            . " AND A.id = :id "
            . " AND M.id = A.message_id "
            . " AND A.status = :status ";
            $smsMessage = $this->conn->prepare($stmt1);
            
            $messId = 1;//$this->mesgId;
            $status = 21;
            
            $smsMessage->bindParam(":id", $messId);
            $smsMessage->bindParam(":status", $status);
            
            $smsMessage->execute();

            // $this->conn->commit();
            return $smsMessage;
            
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