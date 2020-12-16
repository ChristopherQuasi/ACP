<!DOCTYPE html>
<html lang="de" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Start</title>
   <link rel="stylesheet" href="../css/login.css">
   <link rel="shortcut icon" type="images/x-icon" href="../img/logo.icon">
  </head>
  <body>
    <video autoplay muted loop id="myVideo">
      <source src="../img/background.mp4" type="video/mp4">
    </video>

    <form class="login" action="login.php" method="post">
      <?php
      $erfolgreich = "Login erfolgreich (ACP)";
      $passwortfaltsch = "Login Fehlgeschlagen (ACP) (Passwort falsch)";
      $normalerbenutzer = "Login Fehlgeschlagen (ACP) (Normaler Benutzer)";
      $type = 3;
      $date = date("d.m.Y");
      $time = date("H:i:s");
      require("../acp/datamanager.php");
      if(isset($_POST["login"])){
        require("mysql.php");
        $stmt = $mysql->prepare("SELECT * FROM benutzer WHERE USERNAME = :username");
        $stmt->bindParam(":username", $_POST["username"]);
        $stmt->execute();
        $data = $stmt->rowCount();
        if($data == 1){
          while($row = $stmt->fetch()){
            if($row["RANK"] >= 1){
              if(password_verify($_POST["pw"], $row["PASSWORD"])){
                $log = $mysql->prepare("INSERT INTO logs(`ADMINID`,`REASON`,`TYPE`,`DATE`,`TIME`) VALUES(:adminid,:reason,:type,:date,:time)");
                $log->bindParam(":adminid", $row["ID"]);
                $log->bindParam(":reason", $erfolgreich);
                $log->bindParam(":type", $type);
                $log->bindParam(":date", $date);
                $log->bindParam(":time", $time);
                $log->execute();
                session_start();
                $_SESSION["username"] = $row["USERNAME"];
				        $_SESSION["SLAHGDJHASFGAJDHF"] = 154875123;
                header("Location: index.php");
                exit;
              }else{
                $log = $mysql->prepare("INSERT INTO logs(`ADMINID`,`REASON`,`TYPE`,`DATE`,`TIME`) VALUES(:adminid,:reason,:type,:date,:time)");
                $log->bindParam(":adminid", $row["ID"]);
                $log->bindParam(":reason", $passwortfaltsch);
                $log->bindParam(":type", $type);
                $log->bindParam(":date", $date);
                $log->bindParam(":time", $time);
                $log->execute();
                echo "Das angegebene Passwort ist falsch";
              }
            }else{
              $log = $mysql->prepare("INSERT INTO logs(`ADMINID`,`REASON`,`TYPE`,`DATE`,`TIME`) VALUES(:adminid,:reason,:type,:date,:time)");
              $log->bindParam(":adminid", $row["ID"]);
              $log->bindParam(":reason", $normalerbenutzer);
              $log->bindParam(":type", $type);
              $log->bindParam(":date", $date);
              $log->bindParam(":time", $time);
              $log->execute();
              echo "Diesen Benutzer gibt es nicht";
            }
          }
        }else{
          echo "Diesen Benutzer gibt es nicht";
        }
      }

       ?>
    <br>
    <h2>Anmelden</h2>
    <input type="text" name="username" placeholder="Benutzername" required><br>
    <input type="password" name="pw" placeholder="Passwort" required><br>

    <button type="submit" name="login">Login</button>
  </from>
  </body>
</html>
