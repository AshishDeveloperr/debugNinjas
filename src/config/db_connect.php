<?php 

$server = "92.42.111.73";
$username = "debugnin_ninja";
$password = "ninja@123#$";
$database = "debugnin_debug";

$conn = mysqli_connect($server, $username, $password, $database);
if(!$conn){
    echo "error";
}

?>