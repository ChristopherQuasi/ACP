<?php
function issuperadmin($username){
  require("mysql.php");
  $stmt = $mysql->prepare("SELECT RANK FROM benutzer WHERE USERNAME = :username");
  $stmt->bindParam(":username", $username, PDO::PARAM_STR);
  $stmt->execute();
  while($row = $stmt->fetch()){
    if($row["RANK"] == 5){
      return true;
    } else {
      return false;
    }
  }
}


function isHeadadmin($username){
  require("mysql.php");
  $stmt = $mysql->prepare("SELECT RANK FROM benutzer WHERE USERNAME = :username");
  $stmt->bindParam(":username", $username, PDO::PARAM_STR);
  $stmt->execute();
  while($row = $stmt->fetch()){
    if($row["RANK"] >= 4){
      return true;
    } else {
      return false;
    }
  }
}

function isAdmin($username){
  require("mysql.php");
  $stmt = $mysql->prepare("SELECT RANK FROM benutzer WHERE USERNAME = :username");
  $stmt->bindParam(":username", $username, PDO::PARAM_STR);
  $stmt->execute();
  while($row = $stmt->fetch()){
    if($row["RANK"] >= 3){
      return true;
    } else {
      return false;
    }
  }
}
function isSup($username){
  require("mysql.php");
  $stmt = $mysql->prepare("SELECT RANK FROM benutzer WHERE USERNAME = :username");
  $stmt->bindParam(":username", $username, PDO::PARAM_STR);
  $stmt->execute();
  while($row = $stmt->fetch()){
    if($row["RANK"] >= 2){
      return true;
    } else {
      return false;
    }
  }
}

function isonlinesupp($username){
  require("mysql.php");
  $stmt = $mysql->prepare("SELECT RANK FROM benutzer WHERE USERNAME = :username");
  $stmt->bindParam(":username", $username, PDO::PARAM_STR);
  $stmt->execute();
  while($row = $stmt->fetch()){
    if($row["RANK"] == 1){
      return true;
    } else {
      return false;
    }
  }
}

function istsupp($username){
  require("mysql.php");
  $stmt = $mysql->prepare("SELECT RANK FROM benutzer WHERE USERNAME = :username");
  $stmt->bindParam(":username", $username, PDO::PARAM_STR);
  $stmt->execute();
  while($row = $stmt->fetch()){
    if($row["RANK"] >= 1){
      return true;
    } else {
      return false;
    }
  }
}

function isCodeVerify($username){
  require("mysql.php");
  $stmt = $mysql->prepare("SELECT VERIFY FROM teamspeak_user WHERE VERIFYID = :username");
  $stmt->bindParam(":username", $username, PDO::PARAM_STR);
  $stmt->execute();
  while($row = $stmt->fetch()){
    if($row["VERIFY"] >= 3){
      return true;
    } else {
      return false;
    }
  }
}




 ?>
