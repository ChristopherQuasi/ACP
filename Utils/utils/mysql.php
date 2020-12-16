<?php
$host = "localhost";
$database = "sb";
$username = "root";
$password = "";

try {
  $mysql = new PDO("mysql:host=$host;dbname=$database",$username,$password);
  $mysql->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException  $e) {

}

 ?>
