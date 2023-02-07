<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/Functions/show_all_users.php';
header("Content-type: application/json"); 

$allMess = new ShowAllUsers();
// if (isset($_POST['id'])) { // or personal_user_id
 
    // $id = $_POST['id'];
    // echo $id;
$messages = $allMess->show_all_users();
$row = $messages->fetchAll(PDO::FETCH_ASSOC);
print_r($row);
if (count($row) >= 1 ) {
    
    for($i = 0; $i < count($row); $i++){
        $response['exist'] = 'ok';
        $response[$i]["firstName"] = $row[$i]['first_name'];
        $response[$i]["lastName"] = $row[$i]['last_name'];
        $response[$i]["date"] = $row[$i]['reg_date'];
        $response[$i]["time"] = $row[$i]['reg_time'];
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