<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/Functions/show_all_messages.php';
header("Content-type: application/json"); 

$allMess = new ShowAllMessages();
// if (isset($_POST['id'])) { // or personal_user_id
 
    // $id = $_POST['id'];
    // echo $id;
$messages = $allMess->show_all_messages();
$row = $messages->fetchAll(PDO::FETCH_ASSOC);

if (count($row) >= 1 ) {
    
    for($i = 0; $i < count($row); $i++){

        $response['exist'] = 'ok';

        // message info
        $response[$i]["mesgId"] = $row[$i]['mesgId'];
        $response[$i]["text"] = $row[$i]['message'];
        $response[$i]["messageDate"] = $row[$i]['rec_mess_date'].' '.$row[$i]['rec_mess_time'];

        // sender user info
        $response[$i]["userId"] = $row[$i]['userId'];
        $response[$i]["firstName"] = $row[$i]['first_name']; 
        $response[$i]["lastName"] = $row[$i]['last_name'];
        $response[$i]["phoneNumber"] = $row[$i]['phone_number'];
        
    }
    // return $response;
    // print_r($response[0]['text']);
    $j_response = json_encode($response, JSON_UNESCAPED_UNICODE);
    echo $j_response;
} else {
    die("ERROR");
}
// } else {
//     die("Does not get id.");
// }
?>