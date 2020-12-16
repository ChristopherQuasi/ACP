<?php
require("../inc/header.inc.php");
  if(isAdmin($_SESSION["username"])){
    header("Location: unban.php");
    exit;
  }
?>
    <form class="index" action="requestunban.php" method="post">

      <?php
      if(isset($_POST["senden"])){
        require("./mysql.php");
        $stmt = $mysql->prepare("SELECT ID FROM benutzer WHERE USERNAME = :username");
        $stmt->bindParam(":username", $_SESSION["username"]);
        $stmt->execute();
        $adminid = 0;
        while($row = $stmt->fetch()){
          $adminid = $row["ID"];
        }


        $stmt1 = $mysql->prepare("INSERT INTO `unbanrequestion`(`ADMINID`, `BANID`, `REASON`) VALUES (:adminid,:banid,:grund)");
        $stmt1->bindParam(":adminid", $adminid);
        $stmt1->bindParam(":banid", $_POST["banid"]);
        $stmt1->bindParam(":grund", $_POST["Grund"]);
        $stmt1->execute();

      }

       ?>


    <?php if(isset($_GET["require"])){
      $stmt = $mysql->prepare("SELECT * FROM aktivbans WHERE ID = :id");
      $stmt->bindParam(":id", $_GET["require"]);
      $stmt->execute();
      $data = $stmt->rowCount();
      if($data == 1){

      ?>
      <div class="col card hoverable s10 pull-s1 m6 pull-m3 l4 pull-l4">
     <div class="card-content">
       <span class="card-title">Antrag auf Entbannung</span>
       <div class="row">
         <div class="input-field col s12">
           <h6>BanID</h6>
           <input type="number" class="validate" name="banid" value="<?php echo $_GET["require"]; ?>" readonly/>
         </div>
         <div class="input-field col s12">
           <input type="text" class="validate" name="Grund" placeholder="Grund" maxlength="400" required/>
         </div>
     </div>
     <div class="card-action right-align">
       <button type="submit" name="senden" class="waves-effect waves-light btn green">Antrag senden</button>
     </div>
    </div>
    </div>
    </form>
      <?php
    }else{
      ?>
      <div class="center-align">
    <div class="col s12 m6">
      <div class="card red darken-1">
        <div class="card-content white-text">
          <span class="card-title">Fehlgeschlagen</span>
          <p>Es ist ein Fehler aufgetreten</p>
        </div>
      </div>
    </div>
  </div>
      <?php
    }
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
                      $adminname = "null";
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
                          echo '<td><a href="requestunban.php?require='.$row["ID"].'"><i class="fas fa-clipboard-list"></i></a></td>';
                          echo "</tr>";
                        }

                       ?>
                    </tr>
                  </table>
      <?php
    } ?>

  </body>
</html>
