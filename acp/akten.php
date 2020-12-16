<?php
require("../inc/header.inc.php");
require("./mysql.php");


$stmt = $mysql->prepare("SELECT * FROM accounts WHERE USERNAME = :username");
$stmt->bindParam(":username", $_SESSION["username"]);
$stmt->execute();
$RANK = 0;
while($row = $stmt->fetch()){
  $RANK = $row["RANK"];
}
if(isset($_GET["id"])){

  if($_GET["id"] != $_SESSION["STEAMID"]){

      if($RANK >= 2){

      }else{
        header("Location: akten.php?id=". $_SESSION["STEAMID"]);
      }

  }


  ?>
  <div class="container" id="mainPage"><div class="col-s12">
    <?php
    $stmt = $mysql->prepare("SELECT * FROM accounts WHERE STEAMID = :id");
    $stmt->bindParam(":id", $_GET["id"]);
    $stmt->execute();
    $benutzer = "null";
    while($row = $stmt->fetch()){
      $benutzer = $row["USERNAME"];
    }
    ?>
  	<h2>Akte - <?php echo $benutzer ?></h2>


  </div>

  <style>
  .materialize-textarea:focus {
  	border-bottom: 1px solid #ff5722 !important;
  	box-shadow: 0 1px 0 0 #ff5722 !important;
  }
  </style>
  <?php if(isset($_POST["submit"])){
      if($RANK >= 2){
      $datum = date('d.m.Y');
      $id = $_GET["id"];
      $eintrag = $_POST["eintrag"];
      $steamid = $_SESSION["STEAMID"];
      $username = $_SESSION["username"];
      $abc = $mysql->prepare("INSERT INTO records(STEAMID,EINTRAG, TYPE, ERSTELLER, DATUM) VALUES ('$id','$eintrag','$steamid','$username',''$datum)");
      $abc->execute();
  }else{
    echo "Dazu hast du keine Rechte!";
  }
  } ?>

  <div class="col-s12">
  	<div class="input-field col s12">
      <textarea name="eintrag" cols="12" rows="3" maxlength="5000"></textarea>
  		<label for="eintrag" class="grey-text">Beschreibung</label>
  	</div>
    <label>Grund</label>
    <select class="browser-default" name="Grund" onChange="checkOption(this)">
      <option value="0">Auswahl</option>
      <option value="1">Beförderung</option>
      <option value="2">Degradierung</option>
      <option value="3">Zwischenfall</option>
      <option value="4">Rauswurf</option>
      <option value="5">Einstellung</option>
    </select>


  <form class="index" action="akten.php?id=<?php echo $_GET["id"] ?>" method="post">

  		<button class="btn orange" type="submit" name="submit" style="margin-top: 10px; width: 100%">Eintrag hinzufügen</button>
      </form>
  </div>
  <table class="bordered highlight responsive-table" style="table-layout: fixed; word-wrap: break-word;"><thead>
    <tr>
      <th width="15%">Ersteller</th>
      <th width="15%">Datum</th>
      <th width="60%">Eintrag</th>
    </tr>
  </thead>
  <tbody>
    <?php
    $stmt = $mysql->prepare("SELECT * FROM records WHERE STEAMID = :id");
    $stmt->bindParam(":id", $_GET["id"]);
    $stmt->execute();
    while($row = $stmt->fetch()){
      if($row["TYPE"] == 1){
        ?>
        <tr class="green">
        <?php
      }elseif($row["TYPE"] == 2){
        ?>
        <tr class="red">
        <?php
      }else if($row["TYPE"] == 3){
        ?>
        <tr class="yellow">
        <?php
      }else if($row["TYPE"] == 4){
        ?>
        <tr class="orange">
        <?php
      }else if($row["TYPE"] == 5){
        ?>
        <tr class="blue">
        <?php
      }

      ?>

        <td><?php echo $row["ERSTELLER"] ?></td>
        <td><?php echo $row["DATUM"] ?></td>
        <td><?php echo $row["EINTRAG"] ?></td>
        <td class="right">
          <a class="btn-floating btn-small red" href="akten?delte=a"><i class="material-icons">remove</i></a>
        </td>
        </tr>
      <?php
    }
     ?>


  </tbody>



</div>

  <?php

}else{
  ?>
  <div class="container" id="mainPage">
    <div id="logs_page" style="margin-top: 25px;">
      <div class="row">
<?php
require("./mysql.php");
$stmt = $mysql->prepare("SELECT * FROM accounts ORDER BY `RANK` DESC");
$stmt->execute();
while($row = $stmt->fetch()){
  ?>


      <div class="col s6">
        <a href="akten.php?id=<?php echo $row["STEAMID"]; ?>" style="width: 100%; margin-bottom: 25px" class="btn orange"><?php echo $row["USERNAME"] ?></a>
      </div>



  <?php
}

?>
  </div>
</div>
</div>
<?php

 ?>
  <?php
}

 ?>
  </body>
</html>
