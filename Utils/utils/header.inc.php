<?php
require("./utils/mysql.php");
require("./utils/manager.php");


 ?>
<html lang="de">
<head>
    <title>Utils</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/css/materialize.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
    <link rel="stylesheet" href="./css/test.css">
    <link rel="stylesheet" href="./css/jquery.sweet-modal.min.css">
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/js/materialize.min.js"></script>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <link rel="shortcut icon" type="images/x-icon" href="./img/icon.png">
    <script src="./css/jquery.sweet-modal.min.js"></script>
</head>

<?php
session_start();
if(isset($_SESSION)){
  if(!isset($_SESSION['STEAMID'])){
    header("Location: login.php");
    exit;
  }
}else{
  session_start();
  if(!isset($_SESSION['STEAMID'])){
    header("Location: login.php");
    exit;
  }
}

$stmt = $mysql->prepare("SELECT STEAMID FROM accounts WHERE STEAMID = :steamid");
$stmt->bindParam(":steamid", $_SESSION["STEAMID"]);
$stmt->execute();
if($stmt->rowCount() == 0){
  header("Location: logout.php");
  exit;
}

$stmt1 = $mysql->prepare("SELECT * FROM accounts WHERE STEAMID = :steamid");
$stmt1->bindParam(":steamid", $_SESSION["STEAMID"]);
$stmt1->execute();
?>

	<nav>
		<div class="nav-wrapper <?php echo getsitecolor(); ?>">
			<a style="margin-left: 3%;" class="brand-logo">Utils</a>
         <ul class="right">
           <li>
             <a href="index.php">Startseite</a>
           </li>
           <li>
             <a href="stats.php">Statistiken</a>
           </li>
         </ul>
		</div>
	</nav>

</body>
