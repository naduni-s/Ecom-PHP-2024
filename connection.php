<?php
$servername = "localhost";
$username = "Cuser";  
$password = "Craft0077";  
$dbname = "craft_treasure";  

$mysqli = new mysqli($servername, $username, $password, $dbname);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
return $mysqli;
?>
