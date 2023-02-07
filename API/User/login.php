<?php

session_start();
$message="";
 
require_once $_SERVER['DOCUMENT_ROOT'] . '/Functions/add_user_by_manager.php';

$login = new RegisterAndLogin();
// $us = new User();

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    if(isset($_POST['phone_number'])){
        $login->phone_number = trim($_POST['phone_number']);
        //echo 'email';
    }

    $userEx = $login->login();
    $userEx->setFetchMode(PDO::FETCH_ASSOC);
    $ro = $userEx->fetchAll();

    if (count($ro) > 0) {
        $row = $ro[0];
        // print_r($row);
        // echo 8;
        // echo $_POST['password'].'</br>';
        // echo $row['password'].'</br>';
        //$hash = md5(round(0,1000));
        // $passHash = password_hash($_POST['password'], PASSWORD_BCRYPT, [12]);
        // echo $passHash;
        if($_POST['password'] === $row['password']) {
            // echo 6;
            // print_r($row);
            $_SESSION["id"] = $row['id'];
            // echo 7;
            $_SESSION["phone_number"] = $row['phone_number'];
        // echo 3;
            echo 'succesful';
//            $_SESSION["password"] = $row['password'];
        }
    }elseif (count($ro) == 0){
        $message = "Invalid Username or Password!";
    }
}
if(isset($_SESSION["id"])) {
    // echo 9;
    // header("Location:index.php");
    // header("Location:../../FrontEnd/Dashboard/index_active.html"); in bayad ba safhei ke daram jaygozin beshe!
}
// C:\xampp\htdocs\Gargoup\FrontEnd\Dashboard\index.html
