<?php
require("./utils/header.inc.php");
 ?>

 <!DOCTYPE html>
 <html lang="de" dir="ltr">
   <head>
     <meta charset="utf-8">
     <title>Statistiken</title>
   </head>
   <body>
     <div class="container" id="mainPage">
       <div class="white-text">
         <div class="row">
           <div class="col s6">

         <div class="card-panel green">
           <i class="material-icons large right">local_activity</i>
           <?php
           $stmt = $mysql->prepare("SELECT * FROM tm");
           $stmt->execute();

           $tickets = 0;

           while ($row = $stmt->fetch()) {
             $_url = "https://util.suchtbunker.de/googledocsapi/tickets_week.php?s=".$row["STEAMID"];
             $_buffer = implode("", file($_url));
             $abc = (double)strip_tags($_buffer);

             $tickets = $tickets + $abc;
           }

           $anzahl = $stmt->rowCount();

           (double) $ergebnis = $tickets / $anzahl;
            ?>
            <h2>Ø <?php echo round($ergebnis,2) ?> Tickets</h2>
         </div>
         </div>

         <div class="col s6">

         <div class="card-panel blue">
         <i class="material-icons large right">account_circle</i>
         <?php
         $stmt = $mysql->prepare("SELECT * FROM tm");
         $stmt->execute();

         $everything = 0;

         while ($row = $stmt->fetch()) {
           $_url = "https://util.suchtbunker.de/googledocsapi/tickets_week.php?s=".$row["STEAMID"];
           $_buffer = implode("", file($_url));
           $abc = (double)strip_tags($_buffer);

           $again = $mysql->prepare("UPDATE tm SET TICKETS = :ticket WHERE STEAMID = :steamid");
           $again->bindParam(":ticket", $abc);
           $again->bindParam(":steamid", $row["STEAMID"]);
           $again->execute();

           $list[$everything] = $abc;
           $everything++;
         }

         $max = max($list);

         $stmt = $mysql->prepare("SELECT * FROM tm WHERE TICKETS = :ticket");
         $stmt->bindParam(":ticket", $max);
         $stmt->execute();

         $player = "Keiner";

         if($stmt->rowCount() == 1){
           while ($row = $stmt->fetch()) {
             $player = $row["NAME"];
           }
           ?>
           <h4><?php echo $player ?> hat mit <?php echo $max ?> Tickets die meisten!</h4>
           <?php
         }else {
           ?>
          <h4>Mehrere Spieler haben <?php echo $max ?> Tickets</h4>
           <?php
         }


          ?>
         </div>
         </div>

        </div>
       </div>
     </div>
   </body>
 </html>
