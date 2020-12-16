<?php
require("../inc/header.inc.php");
#require("./datamanager.php");
?>
<!DOCTYPE html>
<html lang="de" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Start</title>
  </head>
  <body>
    <?php
    if(isset($_POST["ban"])){
      require("mysql.php");
      $stmt = $mysql->prepare("SELECT ID FROM benutzer WHERE USERNAME = :username");
      $stmt->bindParam(":username", $_SESSION["username"]);
      $stmt->execute();
      $adminid = 0;
      while($row = $stmt->fetch()){
        $adminid = $row["ID"];
      }

      if($_POST["Grund"] == 1){
        header("index.php?error&id1");
      }
      $stmt = $mysql->prepare("SELECT * FROM reason WHERE ID = :id");
      $stmt->bindParam(":id", $_POST["Grund"]);
      $stmt->execute();
      $grund = "Error";
      while($row = $stmt->fetch()){
        $grund = $row["REASON"];
      }

      $stmt = $mysql->prepare("SELECT * FROM teamspeak_user WHERE USERID = :id");
      $stmt->bindParam(":id", $_POST["EindeitigeID"]);
      $stmt->execute();
      $data = $stmt->rowCount();
      if($data == 1){

        $stmt = $mysql->prepare("SELECT * FROM aktivbans WHERE USERID = :id");
        $stmt->bindParam(":id", $_POST["EindeitigeID"]);
        $stmt->execute();
        $data = $stmt->rowCount();
        if($data == 0){


        $stmt = $mysql->prepare("SELECT * FROM teamspeak WHERE USERID = :id");
        $stmt->bindParam(":id", $_POST["EindeitigeID"]);
        $stmt->execute();
        $data = $stmt->rowCount();
        if($data >= 1){
          $stmt = $mysql->prepare("DELETE FROM teamspeak WHERE USERID = :id");
          $stmt->bindParam(":id", $_POST["EindeitigeID"]);
          $stmt->execute();
        }


      $type = 1;

      $datum = date('d.m.Y');
	  $time = date('H:i:s');




      $stmt = $mysql->prepare("INSERT INTO `teamspeak`(`USERID`, `REASON`, `TYPE`) VALUES (:userid,:grund,:type)");
      $stmt->bindParam(":userid", $_POST["EindeitigeID"]);
      $stmt->bindParam(":grund", $grund);
      $stmt->bindParam(":type", $type);
      $stmt->execute();

      $stmt = $mysql->prepare("INSERT INTO `aktivbans`(ADMINID,`USERID`, `REASON`) VALUES (:adminid,:userid,:grund)");
      $stmt->bindParam(":adminid", $adminid);
      $stmt->bindParam(":userid", $_POST["EindeitigeID"]);
      $stmt->bindParam(":grund", $grund);
      $stmt->execute();

      $stmt = $mysql->prepare("INSERT INTO `logs`(`ADMINID`, `USERID`, `REASON`, `TYPE`, `DATE`, TIME) VALUES (:adminid,:userid,:grund,:type,:data,:time)");
      $stmt->bindParam(":adminid", $adminid);
      $stmt->bindParam(":userid", $_POST["EindeitigeID"]);
      $stmt->bindParam(":grund", $grund);
      $stmt->bindParam(":type", $type);
      $stmt->bindParam(":data", $datum);
	  $stmt->bindParam(":time", $time);
      $stmt->execute();
      ?>
      <div class="center-align">
    <div class="col s12 m6">
      <div class="card green darken-1">
        <div class="card-content white-text">
          <span class="card-title">Erfolgreich</span>
          <p>Der Spieler wurde erfolgreich gebannt.</p>
        </div>
      </div>
    </div>
  </div>
      <?php
    }else{
      ?>
      <div class="center-align">
    <div class="col s12 m6">
      <div class="card red darken-1">
        <div class="card-content white-text">
          <span class="card-title">Fehlgeschlagen</span>
          <p>Der Spieler ist bereits gebannt</p>
        </div>
      </div>
    </div>
  </div>
  <?php
    }
    }else{
      ?>
      <div class="center-align">
    <div class="col s12 m6">
      <div class="card red darken-1">
        <div class="card-content white-text">
          <span class="card-title">Fehlgeschlagen</span>
          <p>Der Spieler war noch nie auf dem TeamSpeak</p>
        </div>
      </div>
    </div>
  </div>
      <?php
    }


    }

    if(isset($_POST["kicken"])){
      require("mysql.php");
      $stmt = $mysql->prepare("SELECT ID FROM benutzer WHERE USERNAME = :username");
      $stmt->bindParam(":username", $_SESSION["username"]);
      $stmt->execute();
      $adminid = 0;
      while($row = $stmt->fetch()){
        $adminid = $row["ID"];
      }

      $stmt = $mysql->prepare("SELECT ISONLINE FROM teamspeak_user WHERE USERID = :vid");
      $stmt->bindParam(":vid", $_POST["EindeitigeID"]);
      $stmt->execute();
      $isoinline = 0;
      while($row = $stmt->fetch()){
        $isoinline = $row["ISONLINE"];
      }
      if($isoinline == 1){




      $grund = $_POST["Grund"];
      $type = 0;

      $datum = date('d.m.Y : H:i:s');


      $stmt = $mysql->prepare("INSERT INTO `teamspeak`(`USERID`, `REASON`, `TYPE`) VALUES (:userid,:grund,:type)");
      $stmt->bindParam(":userid", $_POST["EindeitigeID"]);
      $stmt->bindParam(":grund", $grund);
      $stmt->bindParam(":type", $type);
      $stmt->execute();

      $stmt = $mysql->prepare("INSERT INTO `logs`(`ADMINID`, `USERID`, `REASON`, `TYPE`, `DATE`) VALUES (:adminid,:userid,:grund,:type,:data)");
      $stmt->bindParam(":adminid", $adminid);
      $stmt->bindParam(":userid", $_POST["EindeitigeID"]);
      $stmt->bindParam(":grund", $grund);
      $stmt->bindParam(":type", $type);
      $stmt->bindParam(":data", $datum);
      $stmt->execute();
      ?>
      <div class="center-align">
    <div class="col s12 m6">
      <div class="card green darken-1">
        <div class="card-content white-text">
          <span class="card-title">Erfolgreich</span>
          <p>Der Spieler wurde erfolgreich gekickt</p>
        </div>
      </div>
    </div>
  </div>
      <?php
    }else{
      ?>
      <div class="center-align">
    <div class="col s12 m6">
      <div class="card red darken-1">
        <div class="card-content white-text">
          <span class="card-title">Fehlgeschlagen</span>
          <p>Der Spieler ist nicht Online!</p>
        </div>
      </div>
    </div>
  </div>
      <?php
    }

    }


    if(isset($_GET["ban"])){
      ?>
      <form class="index" action="index.php?ban" method="post">

  <div class="col card hoverable s10 pull-s1 m6 pull-m3 l4 pull-l4">
 <div class="card-content">
   <span class="card-title">Ban hinzufügen</span>
   <div class="row">
     <div class="input-field col s12">
       <label for="grund">EindeitigeID</label>
       <input type="text" class="validate" name="EindeitigeID" maxlength="30" required/>
     </div>
     <label>Grund</label>
     <select class="browser-default" name="Grund" onChange="checkOption(this)">
       <?php
                require("./mysql.php");
                $stmt = $mysql->prepare("SELECT * FROM reason");
                $stmt->execute();
                while($row = $stmt->fetch()){
                      echo '<option value="'.$row["ID"].'">'.htmlspecialchars($row["REASON"]).'</option>';

                }
                 ?>
     </select>
   </div>
 </div>
 <div class="card-action right-align">
   <?php if(isonlinesupp($_SESSION["username"])){
     ?><button type="submit" name="ban" class="waves-effect waves-light btn red" disabled>Spieler Bannen</button><?php
   } else{?>
   <button type="submit" name="ban" class="waves-effect waves-light btn red">Spieler Bannen</button>
 <?php } ?>
 </div>
</div>
</form>
      <?php
    }else if(isset($_GET["kick"])){
      ?>
      <form class="index" action="index.php?kick" method="post">

    <div class="col card hoverable s10 pull-s1 m6 pull-m3 l4 pull-l4">
   <div class="card-content">
     <span class="card-title">Kicken</span>
     <div class="row">
       <div class="input-field col s12">
         <label for="EindeitigeID">EindeitigeID</label>
         <input type="text" class="validate" name="EindeitigeID" maxlength="30" required/>
       </div>
       <div class="input-field col s12">
         <label for="Grund">Grund</label>
         <input type="text" class="validate" name="Grund" maxlength="30" required/>
       </div>
   </div>
   <div class="card-action right-align">
     <button type="submit" name="kicken" class="waves-effect waves-light btn red">Kicken</button>
   </div>
  </div>
  </div>
</form>
      <?php
    }else{
      ?>

      <h3>Wilkommen <?php echo $_SESSION["username"]; ?></h3>
      <?php
    }
     ?>
    </form>
    <script type="text/javascript" src="../js/materialize.min.js"></script>
  </body>
</html>
