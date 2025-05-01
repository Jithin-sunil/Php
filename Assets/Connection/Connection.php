<?php
$Servername = "localhost";
$Username = "root";
$Password = "";
$DB="db_shoppify";

$conn = new mysqli($Servername, $Username, $Password, $DB);

if(!$conn){
    die("Connection failed" );
}


?>