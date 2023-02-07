<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/Functions/show_all_messages_and_answers.php';
header("Content-type: application/json"); 

$allMess = new ShowAllMessagesAndAnswers();
// if (isset($_POST['id'])) { // or personal_user_id
 
    // $id = $_POST['id'];
    // echo $id;
$messages = $allMess->show_all_messages_and_answers();
$row = $messages->fetchAll(PDO::FETCH_ASSOC);

if (count($row) >= 1 ) {
    
    for($i = 0; $i < count($row); $i++) {

        $response['exist'] = 'ok';

        // message info
        $response[$i]["mesgId"] = $row[$i]['mesgId'];
        $response[$i]["text"] = $row[$i]['message'];
        $response[$i]["messageDate"] = $row[$i]['rec_mess_date'].' '.$row[$i]['rec_mess_time'];

        // answer info
        $response[$i]["answerId"] = $row[$i]['answerId'];
        $response[$i]["answer"] = $row[$i]['answer_mess'];
        $response[$i]["answerDate"] = $row[$i]['answer_date'].' '.$row[$i]['answer_time'];

        // sender user info
        $response[$i]["userId"] = $row[$i]['userId'];
        $response[$i]["firstName"] = $row[$i]['first_name']; 
        $response[$i]["lastName"] = $row[$i]['last_name'];
        $response[$i]["phoneNumber"] = $row[$i]['phone_number'];
        
    }
    $j_response = json_encode($response, JSON_UNESCAPED_UNICODE);
    echo $j_response;
} else {
    die("ERROR");
}
// } else {
//     die("Does not get id.");
// }
?>