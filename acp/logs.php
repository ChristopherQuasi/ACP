<?php
require("../inc/header.inc.php");

if(!isHeadAdmin($_SESSION["username"])){
  header("Location: index.php");
  exit;
}

if(isset($_POST["a"])){
  header("Location: logs.php?ban");
  exit;
}
if(isset($_POST["b"])){
  header("Location: logs.php?kick");
  exit;
}
if(isset($_POST["c"])){
  header("Location: logs.php?unban");
  exit;
}
if(isset($_POST["d"])){
  header("Location: logs.php?logins");
  exit;
}
if(isset($_POST["e"])){
  header("Location: logs.php?verify");
  exit;
}

if(isset($_POST["verifyfilter"])){
  header("Location: logs.php?verify&filter=null");
  exit;
}

if(isset($_GET["logins"])){
  ?>
              <table>
                <tr>
                  <th>Datum</th>
                  <th>User</th>
                  <th>Notizen</th>
                </tr>
                <tr>
                  <?php
                  $username = "null";
                  require("./mysql.php");
                  $stmt = $mysql->prepare("SELECT * FROM `logs` WHERE TYPE = 3 ORDER BY `logs`.`DATE` AND `logs`.`TIME` DESC");
                  $stmt->execute();
                  while($row = $stmt->fetch()){
                    $stmt1 = $mysql->prepare("SELECT * FROM benutzer WHERE ID = :id");
                    $stmt1->bindParam(":id", $row["ADMINID"]);
                    $stmt1->execute();
                    while($row2 = $stmt1->fetch()){
                      $username = $row2["USERNAME"];
                    }
                      echo "<tr>";
                      echo "<td>". $row["DATE"]."</td>";
                      echo '<td>'. $username .'</td>';
                      echo '<td>'. htmlspecialchars($row["REASON"]) .'</td>';
                      echo "</tr>";


                    }
                   ?>
                </tr>
              </table>

  <form class="index" action="logs.php" method="post">
  <div class="card-action right-align">
    <button type="submit" name="back" class="waves-effect waves-light btn blue">Zurück</button>
  </div>
  </form>
  <?php
}else if(isset($_GET["ban"])){
  ?>
              <table>
                <tr>
                  <th>Datum</th>
                  <th>Admin</th>
                  <th>User</th>
                  <th>Grund</th>
                </tr>
                <tr>
                  <?php
                  $username = "null";
                  require("./mysql.php");
                  $stmt = $mysql->prepare("SELECT * FROM `logs` WHERE TYPE = 1 ORDER BY `logs`.`DATE` AND `logs`.`TIME` DESC");
                  $stmt->execute();
                  while($row = $stmt->fetch()){
                    $stmt1 = $mysql->prepare("SELECT * FROM benutzer WHERE ID = :id");
                    $stmt1->bindParam(":id", $row["ADMINID"]);
                    $stmt1->execute();
                    while($row2 = $stmt1->fetch()){
                      $username = $row2["USERNAME"];
                    }
                      echo "<tr>";
                      echo "<td>". $row["DATE"]."</td>";
                      echo '<td>'. $username .'</td>';
                      echo "<td>". $row["USERID"] . "</td>";
                      echo '<td>'. htmlspecialchars($row["REASON"]) .'</td>';
                      echo "</tr>";


                    }
                   ?>
                </tr>
              </table>

  <form class="index" action="logs.php" method="post">
  <div class="card-action right-align">
    <button type="submit" name="back" class="waves-effect waves-light btn blue">Zurück</button>
  </div>
  </form>
  <?php
}else if(isset($_GET["unban"])){
  echo "Unban";
  ?>
  <form class="index" action="logs.php" method="post">
  <div class="card-action right-align">
    <button type="submit" name="back" class="waves-effect waves-light btn blue">Zurück</button>
  </div>
  </form>
  <?php
}else if(isset($_GET["kick"])){
  echo "Kick";

  ?>
  <form class="index" action="logs.php" method="post">
  <div class="card-action right-align">
    <button type="submit" name="back" class="waves-effect waves-light btn blue">Zurück</button>
  </div>
  </form>
  <?php
}else if(isset($_GET["verify"])){
  $SELECT = "TYPE = 5";

  if(isset($_GET["filter"])){
    $FILTER = $_GET["filter"];
    if($FILTER == "null"){
      echo "IN ARBEIT";
    }else{
      $newselect = $_GET["filter"];
      $SELECT = "TYPE = 5 AND " .$newselect . " ";
    }
  }
  ?>
              <table>
                <tr>
                  <th>Datum</th>
                  <th>User</th>
                  <th>EindeutigeID</th>
                  <th>Action</th>
                </tr>
                <tr>
                  <?php
                  $username = "null";
                  require("./mysql.php");
                  $stmt = $mysql->prepare("SELECT * FROM `logs` WHERE ". $SELECT ." ORDER BY `logs`.`DATE` AND `logs`.`TIME` DESC");
                  $stmt->execute();
                  while($row = $stmt->fetch()){
                    $USERID = "null";
                    $stmt1 = $mysql->prepare("SELECT * FROM logs WHERE TYPE = 5");
                    $stmt1->bindParam(":id", $row["USERID"]);
                    $stmt1->execute();
                    while($row2 = $stmt1->fetch()){
                      $USERID = $row2["USERID"];
                    }
                    $EINDEUTIGEID = "Null";
                    $stmt1 = $mysql->prepare("SELECT * FROM teamspeak_user WHERE VERIFYID = :id");
                    $stmt1->bindParam(":id", $USERID);
                    $stmt1->execute();
                    while($row2 = $stmt1->fetch()){
                      $EINDEUTIGEID = $row2["USERID"];
                    }
                    $USERNAME = "null";
                    $stmt1 = $mysql->prepare("SELECT * FROM benutzer WHERE ID = :id");
                    $stmt1->bindParam(":id", $USERID);
                    $stmt1->execute();
                    while($row2 = $stmt1->fetch()){
                      $USERNAME = $row2["USERNAME"];
                    }
                      echo "<tr>";
                      echo "<td>". $row["DATE"]."</td>";
                      echo '<td>'.$USERNAME.'</td>';
                      echo "<td>". $EINDEUTIGEID ."</td>";
                      echo '<td>'. htmlspecialchars($row["REASON"]) .'</td>';
                      echo "</tr>";


                    }
                   ?>
                </tr>
              </table>

  <form class="index" action="logs.php" method="post">
  <div class="card-action right-align">
    <button type="submit" name="back" class="waves-effect waves-light btn blue">Zurück</button>
    <button type="submit" name="verifyfilter" class="waves-effect waves-light btn blue" disabled>Filter</button>
  </div>
  </form>
  <?php
}else{
  ?>

  <form class="index" action="logs.php" method="post"><br>
  <div class="card-action left-align">
    <button type="submit" name="a" class="waves-effect waves-light btn orange">Ban</button>
    <button type="submit" name="b" class="waves-effect waves-light btn orange">Kick</button>
    <button type="submit" name="c" class="waves-effect waves-light btn orange">unban</button>
    <button type="submit" name="d" class="waves-effect waves-light btn orange">Logins</button>
    <button type="submit" name="e" class="waves-effect waves-light btn orange">Verifys</button>
  </div>
  </form>

  <?php
}

?>
