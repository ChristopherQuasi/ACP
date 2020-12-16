<?php
require("../inc/header.inc.php");

if(isset($_POST["anträge"])){
  header("Location: unban.php?anträge");
  exit;
}

if(isset($_POST["unbana"])){
  header("Location: unban.php?unban");
  exit;
}
if(isset($_GET["unbanid"])){
  require("./mysql.php");
  $stmt = $mysql->prepare("SELECT * FROM aktivbans WHERE ID = :id");
  $stmt->bindParam(":id", $_GET["unbanid"]);
  $stmt->execute();
  $USERID = "null";
  $type = 2;
while($row = $stmt->fetch()){
  $USERID = $row["USERID"];
}
$stmt = $mysql->prepare("INSERT INTO teamspeak(USERID, TYPE) VALUES(:userid, :type)");
$stmt->bindParam(":userid", $USERID);
$stmt->bindParam(":type", $type);
$stmt->execute();

$stmt = $mysql->prepare("DELETE FROM aktivbans WHERE ID = :id");
$stmt->bindParam(":id", $_GET["unbanid"]);
$stmt->execute();
?>
<div class="center-align">
<div class="col s12 m6">
<div class="card green darken-1">
  <div class="card-content white-text">
    <span class="card-title">Erfolgreich</span>
    <p>Der Spieler wurde erfolgreich entbannt</p>
  </div>
</div>
</div>
</div>
<?php
}else if(isset($_GET["anträge"])){
?>
<table>
              <tr>
                <th>Admin</th>
                <th>EindeutigeID</th>
                <th>Bangrund</th>
                <th>Entbannungs Grund</th>
                <th>Aktion</th>
              </tr>
              <tr>
                <?php
                require("./mysql.php");


                $stmt = $mysql->prepare("SELECT * FROM unbanrequestion");
                $stmt->execute();
                $adminname = "null";
                $USERID = "null";
                $REASON = "null";
                while($row = $stmt->fetch()){
                  $stmt1 = $mysql->prepare("SELECT * FROM benutzer WHERE ID = :adminid");
                  $stmt1->bindParam(":adminid", $row["ADMINID"]);
                  $stmt1->execute();
                  while($row1 = $stmt1->fetch()){
                    $adminname = $row1["USERNAME"];
                  }
                  $stmt1 = $mysql->prepare("SELECT * FROM aktivbans WHERE ID = :id");
                  $stmt1->bindParam(":id", $row["BANID"]);
                  $stmt1->execute();
                  while($row1 = $stmt1->fetch()){
                    $USERID = $row1["USERID"];
                    $REASON = $row1["REASON"];
                  }
                    echo "<tr>";
                    echo "<td>". $adminname . "</td>";
                    echo '<td>'.$USERID.'</td>';
                    echo '<td>'.$REASON.'</td>';
                    echo "<td>".$row["REASON"]."</td>";
                    echo '<td><a href="unban.php?accept='.$row["ID"].'"><i class="fas fa-check">  </i></a>';
                    echo '<a href="unban.php?deny='.$row["ID"].'"><i class="fas fa-ban"></i></a></td>';
                    echo "</tr>";
                  }

                 ?>
              </tr>
            </table>
            <form class="index" action="unban.php" method="post">
            <div class="card-action right-align">
              <button type="submit" name="back" class="waves-effect waves-light btn green">Zurück</button>
            </div>
            </form>
<?php

}else if(isset($_GET["unban"])){

  ?>
  <table>
                <tr>
                  <th>Benutzer</th>
                  <th>EindeutigeID</th>
                  <th>Bangrund</th>
                  <th>Ansehen</th>
                </tr>
                <tr>
                  <?php
                  require("./mysql.php");


                  $stmt = $mysql->prepare("SELECT * FROM unbanrequestionuser WHERE STATUS = 0");
                  $stmt->execute();
                  $adminname = "null";
                  $USERID = "null";
                  $REASON = "Fehler";
                  while($row1 = $stmt->fetch()){
                    $stmt = $mysql->prepare("SELECT * FROM teamspeak_user WHERE USERID = :userid");
                    $stmt->bindParam(":userid", $row1["EINDEUTIGEID"]);
                    $stmt->execute();
                    $Benutzerid = "null";
                    while ($row = $stmt->fetch()) {
                      $Benutzerid = $row["VERIFYID"];
                    }

                    $stmt = $mysql->prepare("SELECT * FROM benutzer WHERE ID = :username");
                    $stmt->bindParam(":username", $Benutzerid);
                    $stmt->execute();
                    $benutzername = "null";
                    while ($row = $stmt->fetch()) {
                      $benutzername = $row["USERNAME"];
                    }

                    $stmt = $mysql->prepare("SELECT * FROM aktivbans WHERE USERID = :a");
                    $stmt->bindParam(":a", $row1["EINDEUTIGEID"]);
                    $stmt->execute();
                    while ($row2 = $stmt->fetch()) {
                      $REASON = $row2["REASON"];
                    }

                      echo "<tr>";
                      echo '<td>'.$benutzername.'</td>';
                      echo '<td><a href="history.php?view='.$row1["EINDEUTIGEID"].'">'.$row1["EINDEUTIGEID"].'</a></td>';
                      echo '<td>'.$REASON.'</td>';
                      echo '<td><a href="unban.php?view='.$row1["ID"].'"><i class="far fa-eye"></i></a></td>';
                      echo "</tr>";
                    }

                   ?>
                </tr>
              </table>

  <form class="index" action="unban.php" method="post">
  <div class="card-action right-align">
    <button type="submit" name="back" class="waves-effect waves-light btn green">Zurück</button>
  </div>
  </form>
  <?php

}else if(isset($_GET["view"])){
  if(isset($_POST["acceptunban"])){
    $stmt = $mysql->prepare("SELECT * FROM unbanrequestion WHERE ID = :id");
    $stmt->bindParam(":id", $_GET["accept"]);
    $stmt->execute();
    $BANID = 0;
  while($row = $stmt->fetch()){
    $BANID = $row["BANID"];
  }

  $stmt = $mysql->prepare("SELECT * FROM aktivbans WHERE ID = :id");
  $stmt->bindParam(":id", $BANID);
  $stmt->execute();
  $USERID = "null";
  $type = 2;
  while($row = $stmt->fetch()){
  $USERID = $row["USERID"];
  }

  $stmt = $mysql->prepare("INSERT INTO teamspeak(USERID, TYPE) VALUES(:userid, :type)");
  $stmt->bindParam(":userid", $USERID);
  $stmt->bindParam(":type", $type);
  $stmt->execute();

  $stmt = $mysql->prepare("DELETE FROM aktivbans WHERE ID = :id");
  $stmt->bindParam(":id", $BANID);
  $stmt->execute();

  $stmt = $mysql->prepare("DELETE FROM unbanrequestion WHERE ID = :id");
  $stmt->bindParam(":id", $_GET["accept"]);
  $stmt->execute();

  $stmt = $mysql->prepare("UPDATE unbanrequestionuser SET STATUS = 1 WHERE ID = :id");
  $stmt->bindParam(":id", $_GET["view"]);
  $stmt->execute();
  header("unban.php?unban");
  exit;
  }


  if(isset($_POST["denyunban"])){
    $stmt = $mysql->prepare("UPDATE unbanrequestionuser SET STATUS = 2 WHERE ID = :id");
    $stmt->bindParam(":id", $_GET["view"]);
    $stmt->execute();

    header("unban.php?unban");
    exit;
  }


  $stmt = $mysql->prepare("SELECT * FROM unbanrequestionuser WHERE ID = :id");
  $stmt->bindParam(":id", $_GET["view"]);
  $stmt->execute();
  $data = $stmt->rowCount();
  if($data == 1){
    $USERID = 0;
    $BEGRÜNDUNG = "null";
    $ID = 0;
    $EINDEUTIGEID = "null";
    $TRUEBAN = 0;
    while ($row = $stmt->fetch()) {
      $USERID = $row["USERID"];
      $BEGRÜNDUNG = $row["BEGRÜNDUNG"];
      $ID = $row["ID"];
      $EINDEUTIGEID = $row["EINDEUTIGEID"];
      $TRUEBAN = $row["TRUEBAN"];
    }

    $BENUTZERNAME = "null";
    $stmt = $mysql->prepare("SELECT * FROM benutzer WHERE ID = :id");
    $stmt->bindParam(":id", $USERID);
    $stmt->execute();
    while ($row = $stmt->fetch()) {
      $BENUTZERNAME = $row["USERNAME"];
    }

    if($TRUEBAN == 1){
      $TRUEBAN = "Ja, der Ban ist Gerechtfertigt";
    }else if($TRUEBAN == 0){
      $TRUEBAN = "Nein, der Ban ist nichtgerchtfertigt";

    }
  ?>
  <div class="center-align">
    <form class="index" action="unban.php?view=<?php echo $_GET["view"]  ?>" method="post">

<div class="col card hoverable s10 pull-s1 m6 pull-m3 l4 pull-l4">
<div class="card-content">
 <span class="card-title">Entbannungsantrag von <?php echo $BENUTZERNAME ?></span>
 <div class="row">

    <div class="row">
      <label for="uuid">EindeutigeID</label>
      <input type="text" name="uuid" value="<?php echo $EINDEUTIGEID ?>" maxlength="28" readonly>
    </div>
    <div class="row">
      <label for="Gerechtfertigt">Gerechtfertigt</label>
      <input type="text" name="Gerechtfertigt" value="<?php echo $TRUEBAN ?>" maxlength="28" readonly>
    </div>
   <label>Geschene</label>
   <textarea name="begründung" placeholder="<?php echo $BEGRÜNDUNG?>" rows="100" cols="100"  maxlength="2500" readonly></textarea>
 </div>

</div>
<div class="card-action right-align">
 <button type="submit" name="acceptunban" class="waves-effect waves-light btn green">Annehmen</button>
 <button type="submit" name="denyunban" class="waves-effect waves-light btn red">Ablehnen</button>
</div>
</div>
</form>
  </div>
  <?php
}else{
  echo "Dieser Entbannungsantrag wurde bereis gelöscht";
}

}else if(isset($_GET["accept"])){

  require("./mysql.php");
  $stmt = $mysql->prepare("SELECT * FROM unbanrequestion WHERE ID = :id");
  $stmt->bindParam(":id", $_GET["accept"]);
  $stmt->execute();
  $BANID = 0;
while($row = $stmt->fetch()){
  $BANID = $row["BANID"];
}

$stmt = $mysql->prepare("SELECT * FROM aktivbans WHERE ID = :id");
$stmt->bindParam(":id", $BANID);
$stmt->execute();
$USERID = "null";
$type = 2;
while($row = $stmt->fetch()){
$USERID = $row["USERID"];
}

$stmt = $mysql->prepare("INSERT INTO teamspeak(USERID, TYPE) VALUES(:userid, :type)");
$stmt->bindParam(":userid", $USERID);
$stmt->bindParam(":type", $type);
$stmt->execute();

$stmt = $mysql->prepare("DELETE FROM aktivbans WHERE ID = :id");
$stmt->bindParam(":id", $BANID);
$stmt->execute();

$stmt = $mysql->prepare("DELETE FROM unbanrequestion WHERE ID = :id");
$stmt->bindParam(":id", $_GET["accept"]);
$stmt->execute();


?>
<div class="center-align">
<div class="col s12 m6">
<div class="card green darken-1">
  <div class="card-content white-text">
    <span class="card-title">Erfolgreich</span>
    <p>Der Spieler wurde erfolgreich entbannt</p>
  </div>
</div>
</div>
</div>
<?php
}else if(isset($_GET["deny"])){
  require("./mysql.php");
  $stmt = $mysql->prepare("DELETE FROM unbanrequestion WHERE ID = :id");
  $stmt->bindParam(":id", $_GET["deny"]);
  $stmt->execute();
  ?>
  <div class="center-align">
  <div class="col s12 m6">
  <div class="card green darken-1">
    <div class="card-content white-text">
      <span class="card-title">Erfolgreich</span>
      <p>Der Antrag wurde Abgelehnt</p>
    </div>
  </div>
  </div>
  </div>
  <?php
}else{
  ?>
  <table>
                <tr>
                  <th>Admin</th>
                  <th>EindeutigeID</th>
                  <th>Grund</th>
                  <th>Aktion</th>
                </tr>
                <tr>
                  <?php
                  require("./mysql.php");


                  $stmt = $mysql->prepare("SELECT * FROM aktivbans");
                  $stmt->execute();
                  $adminname = "Console";
                  while($row = $stmt->fetch()){
                    $stmt1 = $mysql->prepare("SELECT * FROM benutzer WHERE ID = :adminid");
                    $stmt1->bindParam(":adminid", $row["ADMINID"]);
                    $stmt1->execute();
                    while($row1 = $stmt1->fetch()){
                      $adminname = $row1["USERNAME"];
                    }
                      echo "<tr>";
                      echo "<td>". $adminname . "</td>";
                      echo '<td>'.$row["USERID"].'</td>';
                      echo "<td>".$row["REASON"]."</td>";
                      echo '<td><a href="unban.php?unbanid='.$row["ID"].'"><i class="far fa-trash-alt"></i></a></td>';
                      echo "</tr>";
                    }

                   ?>
                </tr>
              </table>
              <form class="index" action="unban.php" method="post">
              <div class="card-action right-align">
                <button type="submit" name="anträge" class="waves-effect waves-light btn green">Team Anträge</button>
                <button type="submit" name="unbana" class="waves-effect waves-light btn orange">Entbannungsanträge</button>
              </div>
              </form>

  <?php
}
 ?>

  </body>
</html>
