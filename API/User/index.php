<?php

session_start();

if(isset($_SESSION["phone_number"]) AND !empty($_SESSION['phone_number'])) {

//    echo "<h1> Wellcome.</h1>";
//    echo "<a href='../User/logout.php'>Log Out</a>";

?>

 


<?php

} else {
    echo "<h2>Please login first.</h2>";
}

?>