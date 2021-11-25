<?php
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'id17880676_yusril160');
define('DB_PASSWORD', 'Enigmatics19;');
define('DB_NAME', 'id17880676_pweb');

$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
 
// Check connection
if($link === false){
    die(mysqli_connect_error());
}
?>