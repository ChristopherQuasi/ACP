<?php
require("../inc/header.inc.php");
?>
<!DOCTYPE html>
<html lang="de" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Accounts</title>
    <link type="text/css" rel="stylesheet" href="../css/materialize.min.css"  media="screen,projection"/>
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
    <link rel="shortcut icon" type="images/x-icon" href="../img/logo.icon">
  </head>
  <body>

    <?php
    if(!isHeadAdmin($_SESSION["username"])){
      header("Location: index.php");
      exit;
    } ?>

    <?php
     if(isset($_GET["delete"])){

    }else if(isset($_GET["edit"])){

    }else if(isset($_GET["editrank"])){
      require("./mysql.php");

    }else{
      ?>
      <table>
                    <tr>
                      <th>Benutzername</th>
                      <th>Rank</th>
                      <th>Verify</th>
                      <th>Status</th>
                      <th>Aktion</th>
                    </tr>
                    <tr>
                      <?php
                      require("./mysql.php");

                      $stmt = $mysql->prepare("SELECT * FROM benutzer");
                      $stmt->execute();

                      $status = 0;
                      while($row = $stmt->fetch()){
                          echo "<tr>";
                          echo "<td>". $row["USERNAME"]."</td>";
                          if($row["RANK"] == 0){
                              echo '<td>Benutzer</td>';
                          }else if($row["RANK"] == 1){
                              echo '<td>T-Supporter</td>';
                          }else if($row["RANK"] == 2){
                              echo '<td>Supporter</td>';
                          }else if($row["RANK"] == 3){
                              echo '<td>Admin</td>';
                          }else if($row["RANK"] == 4){
                              echo '<td>Head Admin</td>';
                          }else if($row["RANK"] == 5){
                              echo '<td>Superadmin</td>';
                          }
                          $stmt1 = $mysql->prepare("SELECT * FROM teamspeak_user WHERE VERIFYID = :userid");
                          $stmt1->bindParam(":userid", $row["ID"]);
                          $stmt1->execute();
                          while ($row2 = $stmt1->fetch()) {
                            $status = $row2["VERIFY"];
                          }
                          if($status == 0){
                            echo "<td>Keine Angeben</td>";
                          }else if($status == 1){
                            echo "<td>Nicht Bestätigt</td>";
                          }else if ($status == 2) {
                            echo "<td>Nachricht Gesendet</td>";
                          }else if($status == 3){
                            echo "<td>Verify</td>";
                          }else if($status == 4){
                            echo "<td>Verify</td>";
                          }else{
                            echo "<td>Error</td>";
                          }
                          if($row["STATUS"] == 0){
                            echo "<td>OK</td>";
                          }else if($row["STATUS"] == 1){
                            echo "<td>gesperrt</td>";
                          }

                          if($row["USERNAME"] == $_SESSION["username"]){
                            ?>

                            <div class="card-action right-align">
                              <td><button type="submit" name="login" class="waves-effect waves-light btn blue" disabled><i class="fas fa-pencil-alt"></i></button></td>
                            </div>
                          <?php
                          echo "</tr>";
                          }else{
                            ?>
                            <div class="card-action right-align">
                              <form action="accounts.php?edit&id=<?php echo $row["ID"] ?>" method="post">
                              <td><button type="submit" name="login" class="waves-effect waves-light btn blue" ><i class="fas fa-pencil-alt"></i></button></td>
                                </form>

                        <?php
                          echo "</tr>";
                        }
                        }

                       ?>
                    </tr>
                  </table>

      <?php
    } ?>

  </body>
</html>
