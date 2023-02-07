<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/Functions/add_user_by_manager.php';

$reg = new RegisterAndLogin();
// $u = new User();
 
$error = false;

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    if(isset($_POST['first_name'])){
        // echo 1;
        $reg->first_name = trim($_POST['first_name']);
        
    }

    if(isset($_POST['last_name'])){
        $reg->last_name = trim($_POST['last_name']);
    }

    if(isset($_POST['phone_number'])){
        $phone_number = $reg->sanitize_purpose_phone_input($_POST['phone_number']);
        // echo $phone_number;
        $reg->phone_number = $phone_number;
    }

    if(isset($_POST['password'])){
        $reg->password = $_POST['password'];
    }
    if(strlen($reg->password) < 8){
        $error = true;
        $errorPassword = 'password must at least 8 character.';
    }

    $msg = $reg->check_empty($_POST, array('first_name', 'last_name', 'phone_number', 'password'));
    if($msg) {

        echo $msg;
        echo "<br/><a href='javascript:self.history.back();'>Go Back</a>";

    } elseif($reg->register()) {
        echo '<script>';
        echo 'alert("successfully registered")';
        echo '</script>';
        // header("Location:../../FrontEnd/LoginPage/index_register_login.html");
        // echo '{';
        // echo '"message": "successfully registered."';
        // echo '}';
//        echo "<a href='../User/login.php'></a>";

    } else {
        echo '<script>';
        echo 'alert("Unable to register")';
        echo '</script>';
        // header("Location:../../FrontEnd/LoginPage/index_register_login.html");
    }

}


