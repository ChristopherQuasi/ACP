<?php
require("./utils/mysql.php");
require("./utils/header.inc.php");

?>
<script>
  function filter(){
    $.sweetModal({
      title: 'Filtern',
  	  content: '<?php echo '<form class="index" action="index.php" method="post"><h5>Familie</h5><select class="browser-default" name="family"><option value="1">Pennybutton</option><option value="2">Messina</option><option value="3">Sanders</option></select><button type="submit" name="search" class="btn green right">Filtern</button></form>'; ?>'
    });
  }
  function showregister(){
    $.sweetModal({
      title: 'Registieren',
  	  content: '<?php echo '<form class="index" action="index.php" method="post"><h5>SteamID</h5><input type="text" name="steamid" placeholder="SteamID" required><br><h5>Name</h5><br><input type="text" name="name" placeholder="Name" required><button type="submit" name="register" class="btn green right">Hinzufügen</button></form>'; ?>'
    });
  }
</script>
<?php
if(isset($_POST["search"])){
  header("Location: index.php?filter&family=".$_POST["family"]);
}



 ?>
<!DOCTYPE html>
<html lang="de">
  <body>
    <?php
      $pennybutton = 0;
      $stmt = $mysql->prepare("SELECT * FROM tm WHERE FAMILY = 1");
      $stmt->execute();
      while($row = $stmt->fetch()){
        $_url = "https://util.suchtbunker.de/googledocsapi/tickets_week.php?s=".$row["STEAMID"];
        $_buffer = implode("", file($_url));
        (double)$abc = (double)strip_tags($_buffer);
        (double)$pennybutton = (double)$pennybutton + $abc;
      }
      $messina = 0;
      $stmt = $mysql->prepare("SELECT * FROM tm WHERE FAMILY = 2");
      $stmt->execute();
      while($row = $stmt->fetch()){
        $_url = "https://util.suchtbunker.de/googledocsapi/tickets_week.php?s=".$row["STEAMID"];
        $_buffer = implode("", file($_url));
        $abc = (double)strip_tags($_buffer);
        $messina = $messina + $abc;
      }
      $sanders = 0;
      $stmt = $mysql->prepare("SELECT * FROM tm WHERE FAMILY = 3");
      $stmt->execute();
      while($row = $stmt->fetch()){
        $_url = "https://util.suchtbunker.de/googledocsapi/tickets_week.php?s=".$row["STEAMID"];
        $_buffer = implode("", file($_url));
        $abc = (double)strip_tags($_buffer);
        $sanders = $sanders + $abc;
      }
      $bollo = 0;
      $stmt = $mysql->prepare("SELECT * FROM tm WHERE FAMILY = 4");
      $stmt->execute();
      while($row = $stmt->fetch()){
        $_url = "https://util.suchtbunker.de/googledocsapi/tickets_week.php?s=".$row["STEAMID"];
        $_buffer = implode("", file($_url));
        $abc = (double)strip_tags($_buffer);
        $bollo = $bollo + $abc;
      }

      $meistentickets = "null";


      $winner =  max(array($bollo, $sanders, $messina, $pennybutton));

      if($pennybutton == $winner){
        $meistentickets = "Pennybutton";
      }else if($sanders == $winner){
        $meistentickets = "Sanders";
      }else if($messina == $winner){
        $meistentickets = "Messina";
      }else if($bollo == $winner){
        $meistentickets = "Bollo";
      }

      ?>
      <h5>Am meisten Tickets hat <?php echo $meistentickets ?></h5>
      <button type="submit" class="btn green left" onclick="filter()">Filtern</button>
     <table>
       <th>Teammitglied</th>
       <th>Rang</th>
       <th>Familie</th>
       <th>Tickets</th>
       <th>Stunden Heute</th>

       <?php
       if(isset($_GET["filter"])){
         if(isset($_GET["family"])){
           $stmt = $mysql->prepare("SELECT * FROM tm WHERE FAMILY = :f ORDER BY RANG DESC");
           $stmt->bindParam(":f", $_GET["family"]);
         }else {
           $stmt = $mysql->prepare("SELECT * FROM tm ORDER BY RANG DESC");
         }
       }else{
         $stmt = $mysql->prepare("SELECT * FROM tm ORDER BY RANG DESC");
       }

       $stmt->execute();
       while($row = $stmt->fetch()){
         if($row["RANG"] == 1){
           $rankname = "Azubi";
           ?>
           <tr style="background-color: #93C47D">
             <?php
         }else if($row["RANG"] == 2){
           $rankname = "Third Operator";
           ?>
           <tr style="background-color: #38761D">
           <?php
         }else if($row["RANG"] == 3){
           $rankname = "Second Operator";
           ?>
           <tr style="background-color: #3C78D8">
           <?php
         }else if($row["RANG"] == 4){
           $rankname = "First Operator";
           ?>
           <tr style="background-color: #1A7086">
           <?php
         }else if($row["RANG"] == 5){
           $rankname = "Mentor";
           ?>
           <tr style="background-color: #20C7F2">
           <?php
         }else if($row["RANG"] == 6){
           $rankname = "Admin";
           ?>
           <tr style="background-color: #1C4587">
           <?php
         }else if($row["RANG"] == 7){
           $rankname = "Head Mentor";
           ?>
           <tr style="background-color: #CC0000">
           <?php
         }else if($row["RANG"] == 8){
           $rankname = "Head Admin";
           ?>
           <tr style="background-color: #990000">
           <?php
         }else if($row["RANG"] == 9){
           $rankname = "Leitung";
           ?>
           <tr style="background-color: #990000">
           <?php
         }else if($row["RANG"] == 10){
           $rankname = "Superadmin";
           ?>
           <tr style="background-color: #990000">
           <?php
         }
         ?>

           <td><a href="user.php?s=<?php echo $row["STEAMID"] ?>" style="color: black;"><?php echo $row["NAME"]?></a></td>

            <td><?php echo $rankname; ?></td>
            <?php
            $_url = "https://util.suchtbunker.de/googledocsapi/tickets_week.php?s=".$row["STEAMID"];
            $_buffer = implode("", file($_url));
            $abc = (double)strip_tags($_buffer);
            if($row["FAMILY"] == 1){
              $familyname = "Pennybutton";
            }else if($row["FAMILY"] == 2){
              $familyname = "Messina";
            }else if($row["FAMILY"] == 3){
              $familyname = "Sanders";
            }else if($row["FAMILY"] == 4){
              $familyname = "Bollo";
            }else{
              $familyname = "Keine Familie";
            }
             ?>
             <td><?php echo $familyname ?></td>
            <td><?php echo $abc; ?></td>
            <?php
            $_url1 = "https://util.suchtbunker.de/googledocsapi/playtime.php?&s=".$row["STEAMID"];
            $_buffer1 = implode("", file($_url1));
            $abc1 = strip_tags($_buffer1);
            $abc1 = (double)$abc1 / 60 / 60;
            $abc1 = round($abc1);
             ?>
            <td><?php echo  $abc1 - $row["WEEK"]?></td>

         </tr>
         <?php
       }
        ?>
     </table>

       <div class="modal-footer">
         <br>
         <button type="submit" onclick="showregister()" class="btn green right">Hinzufügen</button>
         <br>
       </div>
  </body>
</html>
