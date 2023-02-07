<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/Functions/last_message_date_and_time.php';

$lastMess = new lastMessageTime();

$messages = $lastMess->last_message_time();
$row = $messages->fetch(PDO::FETCH_ASSOC);

if (count($row) >= 1 ) {
    // $response['exist'] = 'ok';
    $response["date_time"] = $row['rec_mess_date'].' '.$row['rec_mess_time'];
    
    $j_response = json_encode($response, JSON_UNESCAPED_UNICODE);
    echo $j_response;

} else {
    die("ERROR");
}

?>