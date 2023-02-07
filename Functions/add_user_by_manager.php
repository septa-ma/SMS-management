<?php

// require_once $_SERVER['DOCUMENT_ROOT'] . '/message_control_panel/Backend/Tables/userTable.php';

class RegisterAndLogin
{

    private static $SERVER_NAME = "localhost";
    private static $USER_NAME = "root";
    private static $PASSWORD = "";
    private static $DATABASE_NAME = "automation";

    private static $USER = "user";
    private $conn = null;

    public $first_name;
    public $last_name;
    public $phone_number;
    public $password;

    public function __construct(){
        try {

            $this->conn = new PDO("mysql:host=" . self::$SERVER_NAME . ";dbname=" . self::$DATABASE_NAME . ";", self::$USER_NAME, self::$PASSWORD);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->query("set names utf8");
        } catch (Exception $ex) {
            die($ex->getMessage());
        }
    }

    /**
     * @param $u
     * @return bool|PDOStatement
     */
    public function login(){

        try{
            $stmt = $this->conn->prepare( " SELECT id, phone_number, password
				FROM " . self::$USER . "
				WHERE 
				phone_number = :phone_number
                LIMIT 1 " );

            $phone_number = $this->phone_number;
//            $pass = htmlspecialchars(strip_tags($u->getPassword()));
            $stmt->bindParam(":phone_number", $phone_number);
//            $stmt->bindParam(":password", $pass);

            $stmt->execute();
            return $stmt;

        }catch (Exception $ex) {
            die($ex->getMessage());
        }
    }

    // registering function

    /**
     * @param $us
     * @return bool
     */
    public function register(){

        try {

            $query = " INSERT INTO " . self::$USER . " ( first_name, last_name, phone_number, password, delete_key ) 
					 VALUES ( :first_name, :last_name, :phone_number, :password, :delete_key ) ";

            $insertingNewUser = $this->conn->prepare($query);
            
            // echo $phone_number;
            $first_name = htmlspecialchars(strip_tags($this->first_name));
            $last_name = htmlspecialchars(strip_tags($this->last_name));
            $phone_number = $this->phone_number;
            // $var = "user's QRCode is:".$phone_number;
            // $QR_code = hash('sha256', $var);
            // $hash = md5(round(0,1000));
            $passw = htmlspecialchars(strip_tags($this->password));
            // echo $passw;
            $delete_key = 10; // 10 not delete 11 deleted
            
            $insertingNewUser->bindParam(":first_name", $first_name);
            $insertingNewUser->bindParam(":last_name", $last_name);
            $insertingNewUser->bindParam(":phone_number", $phone_number);
            // $insertingNewUser->bindParam(":hash", $hash);
            // hash the password before saving to database
            // $password_hash = md5($password);
            $insertingNewUser->bindParam(":password", $passw);
            $insertingNewUser->bindParam(":delete_key", $delete_key);
            
            $insertingNewUser->execute();
            return true;

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

    public function sanitizeString($var){

        $var = stripslashes($var);
        $var = htmlentities($var);
        $var = strip_tags($var);
        return $var;

    }

    /**
     * @param $input
     * @return int
     */
    public function sanitize_purpose_phone_input($input ) {
        $input = preg_replace( "/[^0-9]/", "", $input );
        if ( strlen( $input ) == 10 ) {
            if ( is_numeric( $input ) ) {
                return intval( $input );
            }
        }
    }

    public function __destruct() {
        // close the database connection
        $this->conn = null;
    }

}
