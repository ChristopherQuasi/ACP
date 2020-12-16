<?php
require("./OpenID/openid.php");
require("./utils/mysql.php");
 ?>

<!DOCTYPE html>
<html lang="de" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Utils | Login</title>
    <link rel="stylesheet" href="./css/login.css">
    <link rel="shortcut icon" href="./img/icon.png">
  </head>
  <body>
    <video autoplay id="myVideo">
      <source src="./files/Nice.mp4" type="video/mp4">
    </video>
    <?php
    if(isset($_COOKIE["utils_login"])){
      $stmt = $mysql->prepare("SELECT * FROM accounts WHERE TOKEN = :remember");
      $stmt->bindParam(":remember", $_COOKIE["utils_login"]);
      $stmt->execute();
      if($stmt->rowCount() == 1){
        while($row = $stmt->fetch()){
          session_start();
          $_SESSION["STEAMID"] = $row["STEAMID"];
          header("Location: index.php");
        }
      }else{
        setcookie("utils_login", "asdasd", time() - 1);
      }
    }
      ?>

    <form class="login" action="?login" method="post">
      <?php
      $_STEAMAPI = "6E2199CD349BD8772C6E93A69EF7FE28";

      try{
      $openid = new LightOpenID('localhost/Utils/index.php');
      if(!$openid->mode){
        if(isset($_GET["login"])){
          $openid->identity = "http://steamcommunity.com/openid/?l=english";
          header("Location: " .$openid->authUrl());
        }else{
          echo "<input type='image' src=./files/login.png>";
        }
      }elseif($openid->mode == "cancel"){
        echo "User has Cansels";
      }else{
        if($openid->validate()){
          $id = $openid->identity;

          $request = explode("/", $id);
          $request_file = end($request);


          $url = "http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=$_STEAMAPI&steamids=".$request_file;
          $json_object = file_get_contents($url);
          $json_decoded = json_decode($json_object);



          foreach($json_decoded->response->players as $player){
            $stmt = $mysql->prepare("SELECT * FROM accounts WHERE STEAMID = :steamid");
            $stmt->bindParam(":steamid", $player->steamid);
            $stmt->execute();
            if($stmt->rowCount() == 1){
              while($row = $stmt->fetch()){

                date_default_timezone_set('Europe/Berlin');
                $dates = date('d.m.Y G:i:s');

                if(empty($row["TOKEN"])){
                  $token = bin2hex(random_bytes(16));

                  $stmt = $mysql->prepare("UPDATE accounts SET TOKEN = :rember WHERE STEAMID = :id");
                  $stmt->bindParam(":rember", $token);
                  $stmt->bindParam(":id", $_SESSION["STEAMID"]);
                  $stmt->execute();

                  setcookie("utils_login", $token, time() + (3600*24*360));
                }else{
                  $token = $row["TOKEN"];
                  setcookie("utils_login", $token, time() + (3600*24*360));

                }
              session_start();
              $_SESSION["STEAMID"] = $row["STEAMID"];

              header("Location: index.php");
            }
          }else{
            echo "Du bist nicht Registriert";
            header("Location: login.php");
          }
        }
        }else{
          header("Location: login.php");
        }
      }
    } catch(ErrorException $e){
      echo $e->getMessage();
    }
       ?>
    </form>
  </body>
</html>
