<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Functions/answer_message.php';

$smsMess = new AnswerMessage();
// $u = new User();
 
$error = false;

if($_SERVER['REQUEST_METHOD'] == 'POST') {

    if(isset($_POST['checkSMS'])) {
        
       $checksms = $_POST['checkSMS'];
    //   echo $checksms;
        
        if( $checksms == 1) {
           
            $messages = $smsMess->sms_message();
            $row = $messages->fetchAll(PDO::FETCH_ASSOC);
            // print_r($row);
            if (count($row) >= 1 ) {
            
                // for($i = 0; $i < count($row); $i++) {
            
                    // $response['exist'] = 'ok';
                    
                    $phoneNum = '0'.$row[0]['phone_number'];
                    $response["phoneNumber"] = $phoneNum;
                    $str = $row[0]['answer_mess'].'**';
                    $response["text"] = $str;
                    
                // }
                print_r($response);
            }
        }
        
    }
    
}