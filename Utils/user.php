<?php
require("./utils/header.inc.php");
require("./utils/mysql.php");

if(isset($_POST["kick"])){
  $stmt = $mysql->prepare("DELETE FROM tm WHERE STEAMID = :steamid");
  $stmt->bindParam(":steamid", $_GET["s"]);
  $stmt->execute();

  showModalRedirect("SUCCESS", "Erfolgreich", $_GET["s"]." wurde erfolgreich rausgeworfen" , "index.php");
}
if(isset($_POST["promote"])){
  $stmt = $mysql->prepare("SELECT * FROM tm WHERE STEAMID = :steamid");
  $stmt->bindParam(":steamid", $_GET["s"]);
  $stmt->execute();

  while($row = $stmt->fetch()){
    $d = $row["RANG"];
    $username = $row["NAME"];
  }

  $d = $d + 1;

  if($d == 1){
    $dn = "Azubi";
  }else if($d == 2){
    $dn = "Third Operator";
  }else if($d == 3){
    $dn = "Second Operator";
  }else if($d == 4){
    $dn = "First Operator";
  }else if($d == 5){
    $dn = "Mentor";
  }else if($d == 6){
    $dn = "Head Mentor";
  }else if($d == 7){
    $dn = "Admin";
  }else if($d == 8){
    $dn = "Head Admin";
  }else if($d == 9){
    $dn = "Leitung";
  }else if($d == 10){
    $dn = "Super Admin";
  }

  if($d >= 5){
    $update = "UPDATE tm SET RANG = " . $d .", FAMILY = 0 WHERE STEAMID = ".$_GET["s"];
  }else{
    $update = "UPDATE tm SET RANG = " . $d ." WHERE STEAMID = ".$_GET["s"];
  }
    $stmt = $mysql->prepare($update);
    $stmt->execute();

    showModalRedirect("SUCCESS", "Erfolgreich", $username." wurde erfolgreich zu ".$dn." befördert" , "user.php?s=".$_GET["s"]);

  }

if(isset($_POST["demote"])){
  $stmt = $mysql->prepare("SELECT * FROM tm WHERE STEAMID = :steamid");
  $stmt->bindParam(":steamid", $_GET["s"]);
  $stmt->execute();

  while($row = $stmt->fetch()){
    $d = $row["RANG"];
    $username = $row["NAME"];
  }
    $d = $d - 1;
    if($d == 1){
      $dn = "Azubi";
    }else if($d == 2){
      $dn = "Third Operator";
    }else if($d == 3){
      $dn = "Second Operator";
    }else if($d == 4){
      $dn = "First Operator";
    }else if($d == 5){
      $dn = "Mentor";
    }else if($d == 6){
      $dn = "Head Mentor";
    }else if($d == 7){
      $dn = "Admin";
    }else if($d == 8){
      $dn = "Head Admin";
    }else if($d == 9){
      $dn = "Leitung";
    }else if($d == 10){
      $dn = "Super Admin";
    }

    $stmt = $mysql->prepare("UPDATE tm SET RANG = :r WHERE STEAMID = :s");
    $stmt->bindParam(":r", $d);
    $stmt->bindParam(":s", $_GET["s"]);
    $stmt->execute();

    showModalRedirect("SUCCESS", "Erfolgreich", $username." wurde erfolgreich zu ".$dn." degradiert" , "user.php?s=".$_GET["s"]);

}

   if(isset($_GET["s"])){
     $stmt = $mysql->prepare("SELECT * FROM tm WHERE STEAMID = :s");
     $stmt->bindParam(":s", $_GET["s"]);
     $stmt->execute();
     if($stmt->rowCount() == 1){
       while($row = $stmt->fetch()){
         $username = $row["NAME"];
       }
       ?>
       <div class="container" id="mainPage">

         <h2>Spielerinfo - <?php  echo $username;?></h2>
          <div class="row">
	           <div class="col s6">
               <a href="https://portal.suchtbunker.de/?p=player&s=<?php echo $_GET["s"] ?>" target="_blank" class="btn <?php echo getsitecolor(); ?>" style="width: 100%;">Portal</a><br><br>
               <a href="https://v1api.suchtbunker.de/steam.to.forum.php?steam=<?php echo $_GET["s"] ?>" class="btn <?php echo getsitecolor(); ?>" target="_blank" style="width: 100%;">Forum</a>
	            </div>
	            <div class="col s6">
                <?php
                if($_SESSION["STEAMID"] == "76561198287878934" || $_SESSION["STEAMID"] == "76561198323285182"){
                  $isinteam = file_get_contents("https://sbpiloten.de/api/inteam.php?f=".$username);

                  echo $abc;

                  if($isinteam == "1"){
                    echo $username ." wurde nicht gefunden\n";
                    echo "Kann es sein das er sich umbenannt hat?";
                  }
                }

                  $stmt = $mysql->prepare("SELECT * FROM tm WHERE STEAMID = :steamid");
                  $stmt->bindParam(":steamid", $_GET["s"]);
                  $stmt->execute();

                  while($row = $stmt->fetch()){
                    $r = $row["RANG"];
                  }
                  if($r == 10){
                    ?>
                    <form class="index" action="user.php?s=<?php echo $_GET["s"] ?>" method="post">
                      <button type="submit" name="kick" class="btn red" style="width: 100%;" >Entlassen</button> <br> <br>
                      <button type="submit" name="asdasdasd" class="btn green" style="width: 100%;" disabled>Befördern</button> <br> <br>
                      <button type="submit" name="demote" class="btn orange" style="width: 100%;">Degradieren</button>
                    </form>

                    <?php
                  }else if($r == 1){
                 ?>
                 <form class="index" action="user.php?s=<?php echo $_GET["s"] ?>" method="post">
                   <button type="submit" name="kick" class="btn red" style="width: 100%;" >Entlassen</button> <br> <br>
                   <button type="submit" name="promote" class="btn green" style="width: 100%;">Befördern</button> <br> <br>
                   <button type="submit" name="aanbfh" class="btn orange" style="width: 100%;" disabled>Degradieren</button>
                 </form>
                 <?php
               }else{
                 ?>
                 <form class="index" action="user.php?s=<?php echo $_GET["s"] ?>" method="post">
                   <button type="submit" name="kick" class="btn red" style="width: 100%;" >Entlassen</button> <br> <br>
                   <button type="submit" name="promote" class="btn green" style="width: 100%;">Befördern</button> <br> <br>
                   <button type="submit" name="demote" class="btn orange" style="width: 100%;">Degradieren</button>
                 </form>
                 <?php
               }
               ?>
	            </div>
              <div width="40%">
                <canvas id="Chart"></canvas>
              </div>
              <div width="40%">
                <canvas id="ticket"></canvas>
              </div>
          </div>
          <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
          <?php
          $week = date("W");
          $stmt = $mysql->prepare("SELECT * FROM onlinetime WHERE STEAMID = :s AND WEEK = :w");
          $stmt->bindParam(":s", $_GET["s"]);
          $stmt->bindParam(":w", $week);
          $stmt->execute();
          if($stmt->rowCount() >= 1){
            while($row = $stmt->fetch()){
              $day1 = $row["1DAY"];
              $day2 = $row["2DAY"];
              $day3 = $row["3DAY"];
              $day4 = $row["4DAY"];
              $day5 = $row["5DAY"];
              $day6 = $row["6DAY"];
              $day7 = $row["7DAY"];
            }
            if(empty($day1)){
              $day1 = 0;
            }
            if(empty($day2)){
              $day2 = 0;
            }
            if(empty($day3)){
              $day3 = 0;
            }
            if(empty($day4)){
              $day4 = 0;
            }
            if(empty($day5)){
              $day5 = 0;
            }
            if(empty($day6)){
              $day6 = 0;
            }
            if(empty($day7)){
              $day7 = 0;
            }

          }else{
            $day1 = 0;
            $day2 = 0;
            $day3 = 0;
            $day4 = 0;
            $day5 = 0;
            $day6 = 0;
            $day7 = 0;
          }
          $stmt = $mysql->prepare("SELECT * FROM weekticktes WHERE STEAMID = :s AND WEEK = :w");
          $stmt->bindParam(":s", $_GET["s"]);
          $stmt->bindParam(":w", $week);
          $stmt->execute();
          if($stmt->rowCount() >= 1){
            while($row = $stmt->fetch()){
              $day12 = $row["1DAY"];
              $day22 = $row["2DAY"];
              $day32 = $row["3DAY"];
              $day42 = $row["4DAY"];
              $day52 = $row["5DAY"];
              $day62 = $row["6DAY"];
              $day72 = $row["7DAY"];
            }
            if(empty($day12)){
              $day12 = 0;
            }
            if(empty($day22)){
              $day22 = 0;
            }
            if(empty($day32)){
              $day32 = 0;
            }
            if(empty($day42)){
              $day42 = 0;
            }
            if(empty($day52)){
              $day52 = 0;
            }
            if(empty($day62)){
              $day62 = 0;
            }
            if(empty($day72)){
              $day72 = 0;
            }

          }else{
            $day12 = 0;
            $day22 = 0;
            $day32 = 0;
            $day42 = 0;
            $day52 = 0;
            $day62 = 0;
            $day72 = 0;
          }
           ?>

          <script>
          var myChart = document.getElementById("Chart");
          var chart =  new Chart(myChart,{
            type: 'line',
            data: {
              labels: ["Montag", "Dienstag", "Mittwoch", "Donnerstag", "Freitag", "Samstag", "Sonntag"],
              datasets:[{
                label: "Stunden dieser Woche",
                borderColor: 'rgba(65,105,255,1)',
                data: [<?php echo $day1 ?>,<?php echo $day2 ?>,<?php echo $day3 ?>,<?php echo $day4 ?>,<?php echo $day5 ?>,<?php echo $day6 ?>,<?php echo $day7 ?>]
              }]
            }
          })
          var ticket = document.getElementById("ticket");
          var chart1 =  new Chart(ticket,{
            type: 'line',
            data: {
              labels: ["Montag", "Dienstag", "Mittwoch", "Donnerstag", "Freitag", "Samstag", "Sonntag"],
              datasets:[{
                label: "Tickets dieser Woche",
                borderColor: 'rgba(65,105,255,1)',
                data: [<?php echo $day12 ?>,<?php echo $day22 ?>,<?php echo $day32 ?>,<?php echo $day42 ?>,<?php echo $day52 ?>,<?php echo $day62 ?>,<?php echo $day72 ?>]
              }]
            }
          })
          </script>
     </div>


       <?php

     }else {
       echo "Person nicht gefunden";
     }

   }else{
     echo "Person nicht gefunden";
   }
  ?>
