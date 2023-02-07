<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Functions/chose_user_for_answering.php';

$user = new ChoseUser();
// $u = new User();
 
$error = false;

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    if(isset($_POST['fullName'])){
        // echo 1;
        $user->fullName = trim($_POST['fullName']);
    }

    if(isset($_POST['mesId'])){
        $user->msgId = trim($_POST['mesId']);
    }

    $msg = $user->check_empty($_POST, array( 'fullName', 'mesId' ));
    if($msg) {

        echo $msg;
        echo "<br/><a href='javascript:self.history.back();'>Go Back</a>";

    } elseif($user->chose_user_for_answering()) {
        echo '<script>';
        echo 'alert("successfull")';
        echo '</script>';
        // header("Location:../../FrontEnd/LoginPage/index_register_login.html");
        // echo '{';
        // echo '"message": "successfully registered."';
        // echo '}';
//        echo "<a href='../User/login.php'></a>";

    } else {
        echo '<script>';
        echo 'alert("Unable to read")';
        echo '</script>';
        // header("Location:../../FrontEnd/LoginPage/index_register_login.html");
    }

}