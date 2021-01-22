<?php 

$db_host="localhost";
$db_username="root";
$db_password="";
$db_name="assignment_v2";

$conn = mysqli_connect($db_host, $db_username, $db_password, $db_name);

//$conn = mysqli_connect('localhost', 'root', '', 'assignment_v2');

if(!$conn){
    echo "Connection Error: " . mysqli_connect_error();
}
