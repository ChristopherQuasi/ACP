<?php
require("./utils/mysql.php");
date_default_timezone_set('Europe/Berlin');
$hour = date("G");
$day = date("N");
$week = date("W");
$dayname = "1DAY";
$tickets = 0;
if($hour == 0){
  $stmt = $mysql->prepare("SELECT * FROM tm");
  $stmt->execute();
  if($stmt->rowCount() >= 1){
    if($day == 1){
      $day = 7;
      $week--;
    }else{
      $day--;
    }
    $dayname = $day."DAY";
    while($row = $stmt->fetch()){
      $_url = "https://util.suchtbunker.de/googledocsapi/tickets_week.php?s=".$row["STEAMID"];
      $_buffer = implode("", file($_url));
      $weektickets = strip_tags($_buffer);

      $tickets = (double)$weektickets - $row["LASTDAYTICKETS"];
      if($day == 1){
        $tickets == $weektickets;
      }

      $selectticketes = $mysql->prepare("SELECT * FROM weekticktes WHERE `STEAMID` = :s AND WEEK = :w");
      $selectticketes->bindParam(":s", $row["STEAMID"]);
      $selectticketes->bindParam(":w", $week);
      $selectticketes->execute();
      if($selectticketes->rowCount() <= 0){
        $setweekticketes = $mysql->prepare("INSERT INTO weekticktes(STEAMID, WEEK) VALUES (:s, :W)");
        $setweekticketes->bindParam(":s", $row["STEAMID"]);
        $setweekticketes->bindParam(":W", $week);
        $setweekticketes->execute();
      }
        $update = "UPDATE weekticktes SET ".$dayname ." = ". $tickets ." WHERE STEAMID = ".$row["STEAMID"] ." AND WEEK = " . $week;

        $updatetickets = $mysql->prepare($update);
        $updatetickets->execute();

        //HOURS
        $_url = "https://util.suchtbunker.de/googledocsapi/playtime.php?&s=".$row["STEAMID"];
        $_buffer = implode("", file($_url));
        $abc = strip_tags($_buffer);
        $abc = (double)$abc/60/60;
        $abc = round($abc);

        $dayhours = (double)$abc - $row["WEEK"];

        $selecthours = $mysql->prepare("SELECT * FROM onlinetime WHERE `STEAMID` = :s AND WEEK = :w");
        $selecthours->bindParam(":s", $row["STEAMID"]);
        $selecthours->bindParam(":w", $week);
        $selecthours->execute();
        if($selecthours->rowCount() <= 0){
          $setweektime = $mysql->prepare("INSERT INTO onlinetime(STEAMID, WEEK) VALUES (:s, :W)");
          $setweektime->bindParam(":s", $row["STEAMID"]);
          $setweektime->bindParam(":W", $week);
          $setweektime->execute();
        }
          $update = "UPDATE onlinetime SET ".$dayname ." = ". $dayhours ." WHERE STEAMID = ".$row["STEAMID"] ." AND WEEK = " . $week;

          $updatetime = $mysql->prepare($update);
          $updatetime->execute();


          $updateupdate = $mysql->prepare("UPDATE tm SET WEEK = :newwek,LASTDAYTICKETS = :lt WHERE STEAMID = :s");
          $updateupdate->bindParam(":newwek", $abc);
          $updateupdate->bindParam(":lt", $weektickets);
          $updateupdate->bindParam(":s", $row["STEAMID"]);
          $updateupdate->execute();
    }
  }
}


 ?>
