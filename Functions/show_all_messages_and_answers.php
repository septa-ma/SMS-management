<?php

class ShowAllMessagesAndAnswers
{

    private static $SERVER_NAME = "localhost";
    private static $USER_NAME = "root";
    private static $PASSWORD = "";
    private static $DATABASE_NAME = "automation";

    private static $USER = "user";
    private static $MESSAGE = "message";
    private static $ANSWER = "answer";
    private $conn = null;

    public function __construct(){
        try {

            $this->conn = new PDO("mysql:host=" . self::$SERVER_NAME . ";dbname=" . self::$DATABASE_NAME . ";", self::$USER_NAME, self::$PASSWORD);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch (Exception $ex) {
            die($ex->getMessage());
        }
    }

    public function show_all_messages_and_answers() {
        try { 
            $stmt = " SELECT M.id AS mesgId, M.message, M.rec_mess_date, M.rec_mess_time, 
            U.id AS userId, U.first_name, U.last_name, U.phone_number, 
            A.id AS answerId, A.answer_mess, A.answer_date, A.answer_time FROM " 
            . self::$MESSAGE . " AS M INNER JOIN " 
            . self::$USER . " AS U INNER JOIN " 
            . self::$ANSWER . " AS A "
            . " WHERE M.user_id_sender = U.id AND "
            . " M.id = A.message_id ";

            $showAllMessage = $this->conn->prepare($stmt);
            $showAllMessage->execute();
            return $showAllMessage;

        } catch(Exception $ex) {
            die($ex->getMessage());
        }
    }

}


?>