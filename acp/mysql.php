<?php

$host = "localhost";
$name = "admin_control";
$user = "root";
$passwort = "";
try{
    $mysql = new PDO("mysql:host=$host;dbname=$name", $user, $passwort);
    $mysql->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e){
        ?>
        <div class="error">
            <h3>Fehler</h3>
            <p><?php echo $e->getMessage() ?></p>
        </div>
        <?php

}
 ?>
