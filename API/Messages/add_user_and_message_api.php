<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Functions/add_user_and_message.php';

$mess = new CheckUserAndSaveMessage();
 
$error = false;

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    if(isset($_POST['first_name'])){
        $mess->first_name = trim($_POST['first_name']);
    }

    if(isset($_POST['last_name'])){
        $mess->last_name = trim($_POST['last_name']);
    }
    
     if(isset($_POST['date'])){
        $mess->date = trim($_POST['date']);
    }
    
    if(isset($_POST['time'])){
        $mess->time = trim($_POST['time']);
    }

    if(isset($_POST['phone_number'])){
        $phone_number = $mess->sanitize_purpose_phone_input($_POST['phone_number']);
        $mess->phone_number = $phone_number;
    }

    if(isset($_POST['message'])){
        $mess->message = trim($_POST['message']);
    }
    
    $msg = $mess->check_empty($_POST, array('first_name', 'last_name', 'date', 'time', 'phone_number', 'message'));
    if($msg) {

        echo $msg;
        echo "<br/><a href='javascript:self.history.back();'>Go Back</a>";

    } elseif($mess->check_user_and_save_message()==false) {
        echo '<script>';
        echo 'alert("successfully registered")';
        echo '</script>';

    } else {
        echo '<script>';
        echo 'alert("Unable to register")';
        echo '</script>';
    }

}