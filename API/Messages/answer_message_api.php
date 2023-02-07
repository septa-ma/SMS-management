<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Functions/answer_message.php';

$ans = new AnswerMessage();
// $u = new User();
 
$error = false;

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    if(isset($_POST['id'])){
        $ans->mesgId = $_POST['id'];
    }

    if(isset($_POST['message'])){
        $ans->message = trim($_POST['message']);
    }

    $msg = $ans->check_empty($_POST, array('id', 'message'));
    if($msg) {

        echo $msg;
        echo "<br/><a href='javascript:self.history.back();'>Go Back</a>";

    } elseif($ans->answer_message()) {
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