<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/Functions/show_one_messages.php';

$allMess = new ShowOneMessage();
if (isset($_POST['id'])) { // or personal_user_id
 
    $id = $_POST['id'];
    // echo $id;
    $messages = $allMess->show_one_message($id);
    $row = $messages->fetchAll(PDO::FETCH_ASSOC);

    if (count($row) >= 1 ) {
        // print_r(count($rows));
        $response['exist'] = 'ok';
        $response["text"] = $row[0]['message'];
        $response["date"] = $row[0]['rec_mess_date'];
        $response["time"] = $row[0]['rec_mess_time'];
        $response["firstName"] = $row[0]['first_name'];
        $response["lastName"] = $row[0]['last_name'];
        $response["phoneNumber"] = $row[0]['phone_number'];
        
        $j_response = json_encode($response, JSON_UNESCAPED_UNICODE);
        echo $j_response;
        // print_r($response);

    } else {
        die("ERROR");
    }

}
?>